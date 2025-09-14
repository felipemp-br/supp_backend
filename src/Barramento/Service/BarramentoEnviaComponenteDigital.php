<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource as TramitacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository as JuntadaRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Classe responsável por realizar envio e recebimento de componentes digitais pelo Barramento.
 */
class BarramentoEnviaComponenteDigital extends AbstractBarramentoManager
{
    /**
     * ID da tramitação do Barramento PEN.
     */
    private int $idt;

    private ?int $ticketParaEnvioDeComponentesDigitais;

    private string $transactionId;

    private JuntadaRepository $juntadaRepository;

    private StatusBarramentoResource $statusBarramentoResource;

    private ProcessoResource $processoResource;

    private OrigemDadosResource $origemDadosResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    private TramitacaoResource $tramitacaoResource;

    private ModalidadeNotificacaoResource $modalidadeNotificacaoResource;

    private NotificacaoResource $notificacaoResource;

    protected array $config;

    /**
     * @param BarramentoLogger $logger
     * @param ParameterBagInterface $parameterBag
     * @param BarramentoClient $barramentoClient
     * @param ProcessoResource $processoResource
     * @param OrigemDadosResource $origemDadosResource
     * @param StatusBarramentoResource $statusBarramentoResource
     * @param JuntadaRepository $juntadaRepository
     * @param VinculacaoProcessoResource $vinculacaoProcessoResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TramitacaoResource $tramitacaoResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param NotificacaoResource $notificacaoResource
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        StatusBarramentoResource $statusBarramentoResource,
        JuntadaRepository $juntadaRepository,
        private VinculacaoProcessoResource $vinculacaoProcessoResource,
        ComponenteDigitalResource $componenteDigitalResource,
        TramitacaoResource $tramitacaoResource,
        ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        NotificacaoResource $notificacaoResource
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->logger = $logger;
        $this->client = $barramentoClient;
        $this->juntadaRepository = $juntadaRepository;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->modalidadeNotificacaoResource = $modalidadeNotificacaoResource;
        $this->notificacaoResource = $notificacaoResource;
    }

    /**
     * Envia componentes digitais de um tramite pendente no barramento.
     *
     * Efetua o envio do componente digital (sua representação binária) para a solução. Após o envio de
     * todos os componentes digitais do trâmite, ele segue para o destinatário.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function enviaComponentesDigitais(int $idt, string $transactionId)
    {
        $this->idt = $idt;
        $this->transactionId = $transactionId;

        $response = null;

        try {
            $objetoComponenteDigital = $this->getObjetoComponenteDigital();

            if ($objetoComponenteDigital instanceof DocumentoAvulsoEntity) {
                $response = $this->enviarComponentesDigitaisDocumentoAvulso($objetoComponenteDigital);
            }

            if ($objetoComponenteDigital instanceof ProcessoEntity) {
                $response = $this->enviarComponentesDigitaisProcesso($objetoComponenteDigital);
            }

            // atualiza o status do barramento
            if ($response) {
                /** @var StatusBarramento $statusBarramentoEntity */
                $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(['idt' => $this->idt]);
                $this->atualizaStatusTramitacao($statusBarramentoEntity, self::SIT_DOC_PROC_RECEBIDO_DESTINATARIO);
            }
        } catch (Throwable $e) {
            $this->logger->critical("Ocorreu um erro ao tentar consultar o tramite [$idt]. Erro: {$e->getMessage()}");
            throw $e;
        }

        return $response;
    }

    /**
     * Retorna o objeto relacionado ao $this->idt que poderá ser uma Processo ou Documento Avulso.
     *
     * @return DocumentoAvulsoEntity|ProcessoEntity
     *
     * @throws Exception o trâmite não está associado a nenhum objeto do SUPP
     */
    private function getObjetoComponenteDigital()
    {
        $retorno = false;
        /** @var StatusBarramento $statusBarramento */
        $statusBarramento = $this->statusBarramentoResource->findOneBy(['idt' => $this->idt]);

        if ($statusBarramento) {
            if ($statusBarramento->getDocumentoAvulso()) {
                $retorno = $statusBarramento->getDocumentoAvulso();
            }
            if ($statusBarramento->getProcesso()) {
                $retorno = $statusBarramento->getProcesso();
            }

            $this->ticketParaEnvioDeComponentesDigitais = $statusBarramento->getIdtComponenteDigital();
        }

        if (!$retorno) {
            throw new Exception('O trâmite não está associado a nenhum objeto do SUPP.');
        }

        return $retorno;
    }

    /**
     * Envia componentes digitais referentes a um documento avulso.
     *
     * Efetua o envio do componente digital (sua representação binária) para a solução. Após o envio de
     * todos os componentes digitais do trâmite, ele segue para o destinatário.
     *
     * @return mixed
     *
     * @throws Exception
     */
    private function enviarComponentesDigitaisDocumentoAvulso(DocumentoAvulsoEntity $documentoAvulsoEntity)
    {
        $componentesDigitais = $this->documentoAvulsoToComponentesDigitais($documentoAvulsoEntity);

        $response = null;
        foreach ($componentesDigitais as $params) {
            $params->dadosDoComponenteDigital->ticketParaEnvioDeComponentesDigitais =
                $this->ticketParaEnvioDeComponentesDigitais;

            $responseClient = $this->client->enviarComponenteDigital($params);

            if (!$responseClient) {
                /** @var StatusBarramento $statusBarramentoEntity */
                $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(['idt' => $this->idt]);
                $this->atualizaStatusTramitacao($statusBarramentoEntity, self::SIT_CANCELADO);

                throw new Exception('Falha ao tentar enviar o componente digital: '.
                    "[{$params->dadosDoComponenteDigital->hashDoComponenteDigital}]. ".
                    "{$this->client->getMensagemErro()}");
            }

            $response[] = $responseClient;
        }

        return $response;
    }

    /**
     * Obtém todos os componentes digitais de uma comunicação.
     *
     * @throws Exception
     */
    private function documentoAvulsoToComponentesDigitais(DocumentoAvulsoEntity $documentoAvulsoEntity): array
    {
        $componentesDigitais = [];

        if (!$documentoAvulsoEntity) {
            throw new Exception('Documento Avulso não localizado.');
        }

        $componentes = $documentoAvulsoEntity->getDocumentoRemessa()->getComponentesDigitais();

        /** @var ComponenteDigitalEntity $componenteDigitalSupp */
        foreach ($componentes as $componenteDigitalSupp) {
            $componenteDigitalSupp->setConteudo(
                $this->componenteDigitalResource->download(
                    $componenteDigitalSupp->getId(),
                    $this->transactionId
                )->getConteudo()
            );

            $componenteDigital = new stdClass();
            $componenteDigital->dadosDoComponenteDigital = new stdClass();
            $componenteDigital->dadosDoComponenteDigital
                ->protocolo = $documentoAvulsoEntity->getProcesso()->getNUP().'_'.$documentoAvulsoEntity->getId();
            $componenteDigital->dadosDoComponenteDigital->hashDoComponenteDigital = base64_encode(
                hash('SHA256', (string) $componenteDigitalSupp->getConteudo(), true)
            );
            $componenteDigital->dadosDoComponenteDigital
                ->conteudoDoComponenteDigital = $componenteDigitalSupp->getConteudo();
            $componentesDigitais[] = $componenteDigital;
        }

        $vinculacoes = $documentoAvulsoEntity->getDocumentoRemessa()->getVinculacoesDocumentos();

        /* @var VinculacaoDocumentoEntity $vinculacao  */
        foreach ($vinculacoes as $vinculacao) {
            /** @var ComponenteDigitalEntity $componenteDigitalSupp */
            foreach ($vinculacao->getDocumentoVinculado()->getComponentesDigitais() as $componenteDigitalSupp) {
                $componenteDigitalSupp->setConteudo(
                    $this->componenteDigitalResource->download(
                        $componenteDigitalSupp->getId(),
                        $this->transactionId
                    )->getConteudo()
                );

                $componenteDigital = new stdClass();
                $componenteDigital->dadosDoComponenteDigital = new stdClass();
                $componenteDigital->dadosDoComponenteDigital
                    ->protocolo = $documentoAvulsoEntity->getProcesso()->getNUP().'_'.$documentoAvulsoEntity->getId();
                $componenteDigital->dadosDoComponenteDigital->hashDoComponenteDigital = base64_encode(
                    hash('SHA256', $componenteDigitalSupp->getConteudo(), true)
                );
                $componenteDigital->dadosDoComponenteDigital
                    ->conteudoDoComponenteDigital = $componenteDigitalSupp->getConteudo();
                $componentesDigitais[] = $componenteDigital;
            }
        }

        return $componentesDigitais;
    }

    /**
     * Envia componentes digitais referentes a um processo.
     *
     * Efetua o envio do componente digital (sua representação binária) para a solução. Após o envio de
     * todos os componentes digitais do trâmite, ele segue para o destinatário.
     *
     * @return mixed
     *
     * @throws Exception
     */
    private function enviarComponentesDigitaisProcesso(ProcessoEntity $processoEntity)
    {
        $componentesDigitais = $this->processoToComponentesDigitais($processoEntity);

        // **DESATIVADO** temporariamente por limitação no SEI
//        $vinculacoes = $this->vinculacaoProcessoResource->getRepository()->findBy(
//            ['processo' => $processoEntity]
//        );
//
//        if (count($vinculacoes) > 0) {
//            foreach ($vinculacoes as $vinculacao) {
//                if ('APENSAMENTO' == $vinculacao->getModalidadeVinculacaoProcesso()->getValor()) {
//                    $componentesDigitaisApensado =
//                      $this->processoToComponentesDigitais($vinculacao->getProcessoVinculado());
//                    foreach ($componentesDigitaisApensado as $componente) {
//                        $componentesDigitais[] = $componente;
//                    }
//                }
//            }
//        }

        $response = null;
        foreach ($componentesDigitais as $params) {
            $params->dadosDoComponenteDigital->ticketParaEnvioDeComponentesDigitais =
                $this->ticketParaEnvioDeComponentesDigitais;
            $responseClient = $this->client->enviarComponenteDigital($params);
            if (!$responseClient) {
                /** @var StatusBarramento $statusBarramentoEntity */
                $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(['idt' => $this->idt]);
                $this->atualizaStatusTramitacao($statusBarramentoEntity, self::SIT_CANCELADO);

                throw new Exception('Falha ao tentar enviar o componente digital: '.
                    "[{$params->dadosDoComponenteDigital->hashDoComponenteDigital}]. ".
                    "{$this->client->getMensagemErro()}");
            }
            $response[] = $responseClient;
        }

        return $response;
    }

    /**
     * Obtém todos os componentes digitais de um processo.
     *
     * @throws Exception Processo não informado
     */
    private function processoToComponentesDigitais(ProcessoEntity $processoEntity): array
    {
        $componentesDigitais = [];
        $inicio_sincronizacao = false;

        if (!$processoEntity) {
            throw new Exception('Processo não localizado.');
        }

        /** @var Juntada $juntadas */
        $juntadas = $this->juntadaRepository->findJuntadaByProcesso($processoEntity);

        /* @var Juntada $juntadaSUPP */
        foreach ($juntadas as $juntadaSUPP) {
            //verifica se era processo existente, desconsidera as juntadas antigas
            if ($processoEntity->getOrigemDados()?->getServico() === 'barramento_existente'
                && !$inicio_sincronizacao) {
                if (!$juntadaSUPP->getOrigemDados()) {
                    continue;
                } else {
                    $inicio_sincronizacao = true;
                }
            }

            // só manda se não estiver desentranhado
            if ($juntadaSUPP->getAtivo()) {
                /** @var ComponenteDigitalEntity $componenteDigitalSUPP */
                foreach ($juntadaSUPP->getDocumento()->getComponentesDigitais() as $k => $componenteDigitalSUPP) {
                    // validacao para componentes digitais duplicados
                    if ($juntadaSUPP->getDocumento()->getComponentesDigitais()->count() > 1 && $k > 0 &&
                        $componenteDigitalSUPP->getOrigemDados() &&
                        $componenteDigitalSUPP->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN' &&
                        $componenteDigitalSUPP->getHash() ===
                            $juntadaSUPP->getDocumento()->getComponentesDigitais()->get($k-1)->getHash() &&
                        $componenteDigitalSUPP->getFileName() ===
                        $juntadaSUPP->getDocumento()->getComponentesDigitais()->get($k-1)->getFileName()) {
                            continue;
                    }

                    $componenteDigitalSUPP->setConteudo(
                        $this->componenteDigitalResource->download(
                            $componenteDigitalSUPP->getId(),
                            $this->transactionId
                        )->getConteudo()
                    );

                    $componenteDigital = new stdClass();
                    $componenteDigital->dadosDoComponenteDigital = new stdClass();
                    if ($processoEntity->getOrigemDados() &&
                        $processoEntity->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN' &&
                        $processoEntity->getOutroNumero()) {
                        $componenteDigital->dadosDoComponenteDigital->protocolo = $processoEntity->getOutroNumero();
                    } else {
                        $componenteDigital->dadosDoComponenteDigital->protocolo = $processoEntity->getNUPFormatado();
                    }

                    $hash = base64_encode(
                        hash('SHA256', (string) $componenteDigitalSUPP->getConteudo(), true)
                    );

                    $componenteDigital->dadosDoComponenteDigital->hashDoComponenteDigital = $hash;
                    $componenteDigital->dadosDoComponenteDigital
                            ->conteudoDoComponenteDigital = $componenteDigitalSUPP->getConteudo();
                        $componentesDigitais[] = $componenteDigital;
                }
            }
        }

        return $componentesDigitais;
    }

    /**
     * @param $statusBarramentoEntity
     * @param $status
     * @throws AnnotationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    private function atualizaStatusTramitacao($statusBarramentoEntity, $status): void
    {
        if ($statusBarramentoEntity) {
            /** @var StatusBarramentoDTO $statusBarramentoDto */
            $statusBarramentoDto = $this->statusBarramentoResource->getDtoForEntity(
                $statusBarramentoEntity->getId(),
                StatusBarramentoDTO::class
            );

            if ($this->client->getMensagemErro()) {
                $statusBarramentoDto->setMensagemErro($this->client->getMensagemErro());
            }

            if ($this->client->getCodigoErro()) {
                $statusBarramentoDto->setCodigoErro((int) $this->client->getCodigoErro());
            }

            $statusBarramentoDto->setCodSituacaoTramitacao($status);

            $statusBarramentoEntity = $this->statusBarramentoResource->update(
                $statusBarramentoEntity->getId(),
                $statusBarramentoDto,
                $this->transactionId
            );

            if ($statusBarramentoEntity && $status === 7) {
                // recebe a remessa
                $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                    $statusBarramentoEntity->getTramitacao()->getId(),
                    Tramitacao::class
                );
                $tramitacaoDto->setDataHoraRecebimento(new DateTime());
                $tramitacaoEntity = $this->tramitacaoResource->update(
                    $statusBarramentoEntity->getTramitacao()->getId(),
                    $tramitacaoDto,
                    $this->transactionId
                );

                // cria notificação para o usuário
                $this->notificaUsuario($tramitacaoEntity, $this->transactionId);
            }
        }
    }

    /**
     * @param $tramitacaoEntity
     * @param $transactionId
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function notificaUsuario(
        $tramitacaoEntity,
        $transactionId
    ): void {
        $tempo = new DateTime();
        $tempo->add(new DateInterval('P30D'));

        /** @var ModalidadeNotificacao $modalidadeNotificacaoEntity */
        $modalidadeNotificacaoEntity = $this->modalidadeNotificacaoResource->findOneBy(['valor' => 'SISTEMA']);

        //$notificacaoDto = $this->notificacaoResource->getDtoClass();
        $notificacaoDto = new Notificacao();
        $notificacaoDto->setDestinatario($tramitacaoEntity->getCriadoPor());
        $notificacaoDto->setModalidadeNotificacao($modalidadeNotificacaoEntity);
        $notificacaoDto->setConteudo(
            'TRAMITAÇÃO VIA INTEGRAÇÃO/BARRAMENTO NO NUP '.$tramitacaoEntity->getProcesso()->getNUP().
            ' FALHOU! HOUVE A EXCLUSÃO DA TRAMITAÇÃO ID '.$tramitacaoEntity->getId().' DE MANEIRA AUTOMÁTICA!'
        );
        $notificacaoDto->setDataHoraExpiracao($tempo);
        $notificacaoDto->setUrgente(true);

        $this->notificacaoResource->create($notificacaoDto, $transactionId);
    }
}
