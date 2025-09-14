<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Volume as VolumeEntity;
use Twig\Environment;
use function microtime;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource as DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeVinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource as OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeFaseRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos diversos de processos entre o Supp e o Barramento.
 */
class BarramentoDocumentoManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Mapeamento dos tipos de documentos utilizado no barramento.
     */
    private array $mapTipoDocumento;

    private JuntadaResource $juntadaResource;

    private JuntadaRepository $juntadaRepository;

    private TipoDocumentoResource $tipoDocumentoResource;

    private VolumeResource $volumeResource;

    private ModalidadeVinculacaoDocumentoResource $modalidadeVinculacaoDocumentoResource;

    private VinculacaoDocumentoResource $vinculacaoDocumentoResource;

    private string $transactionId;

    private DocumentoResource $documentoResource;

    private OrigemDadosResource $origemDadosResource;

    private TipoDocumentoRepository $tipoDocumentoRepository;

    private ClassificacaoResource $classificacaoResource;

    private EspecieProcessoRepository $especieProcessoRepository;

    private ModalidadeMeioResource $modalidadeMeioResource;

    private ProcessoResource $processoResource;

    private ModalidadeVinculacaoProcessoRepository $modalidadeVinculacaoProcessoRepository;

    private VinculacaoProcessoResource $vinculacaoProcessoResource;

    private TransactionManager $transactionManager;

    private ModalidadeFaseRepository $modalidadeFaseRepository;

    private NUPProviderManager $nupProviderManager;

    private SetorResource $setorResource;

    /**
     * BarramentoDocumentoManager constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        JuntadaResource $juntadaResource,
        JuntadaRepository $juntadaRepository,
        VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        ModalidadeVinculacaoDocumentoResource $modalidadeVinculacaoDocumentoResource,
        TipoDocumentoResource $tipoDocumentoResource,
        VolumeResource $volumeResource,
        DocumentoResource $documentoResource,
        OrigemDadosResource $origemDadosResource,
        TipoDocumentoRepository $tipoDocumentoRepository,
        ClassificacaoResource $classificacaoResource,
        EspecieProcessoRepository $especieProcessoRepository,
        ModalidadeMeioResource $modalidadeMeioResource,
        ProcessoResource $processoResource,
        VinculacaoProcessoResource $vinculacaoProcessoResource,
        ModalidadeVinculacaoProcessoRepository $modalidadeVinculacaoProcessoRepository,
        TransactionManager $transactionManager,
        ModalidadeFaseRepository $modalidadeFaseRepository,
        private ParameterBagInterface $parameterBag,
        NUPProviderManager $NUPProviderManager,
        SetorResource $setorResource,
        private ComponenteDigitalResource $componenteDigitalResource,
        private Environment $twig
    ) {
        $this->logger = $logger;
        $this->mapTipoDocumento =
            $parameterBag->get('integracao_barramento')['mapeamentos']['tipo_documento_barramento'];
        $this->juntadaResource = $juntadaResource;
        $this->juntadaRepository = $juntadaRepository;
        $this->vinculacaoDocumentoResource = $vinculacaoDocumentoResource;
        $this->modalidadeVinculacaoDocumentoResource = $modalidadeVinculacaoDocumentoResource;
        $this->tipoDocumentoResource = $tipoDocumentoResource;
        $this->volumeResource = $volumeResource;
        $this->documentoResource = $documentoResource;
        $this->origemDadosResource = $origemDadosResource;
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
        $this->classificacaoResource = $classificacaoResource;
        $this->especieProcessoRepository = $especieProcessoRepository;
        $this->modalidadeMeioResource = $modalidadeMeioResource;
        $this->processoResource = $processoResource;
        $this->modalidadeVinculacaoProcessoRepository = $modalidadeVinculacaoProcessoRepository;
        $this->vinculacaoProcessoResource = $vinculacaoProcessoResource;
        $this->transactionManager = $transactionManager;
        $this->modalidadeFaseRepository = $modalidadeFaseRepository;
        $this->nupProviderManager = $NUPProviderManager;
        $this->setorResource = $setorResource;
    }

    /**
     * Sincroniza documentos a partir de metadados de um processo recebido pelo barramento.
     *
     * @param $metadadosProcesso
     * @param $volume
     *
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function sincronizaDocumentos(
        $metadadosProcesso,
        Processo $processo,
        $volume,
        string $transactionId,
        string $nre
    ): void {
        $this->transactionId = $transactionId;
        if (!is_array($metadadosProcesso->documento)) {
            $metadadosProcesso->documento = [
                $metadadosProcesso->documento,
            ];
        }

        $juntadasSupp = [];
        $inicio_sinc_processo_existente = 0;

        if ($processo->getId()) {
            /** @var VolumeEntity $volume */
            foreach ($processo->getVolumes() as $volume) {
                foreach ($volume->getJuntadas() as $juntada) {
                    // para processos ja existentes na base descarta juntadas anteriores
                    if ($processo->getOrigemDados()?->getServico() === 'barramento_existente'
                        && !$inicio_sinc_processo_existente) {
                        if (!$juntada->getOrigemDados()) {
                            continue;
                        } else {
                            $inicio_sinc_processo_existente = $juntada->getNumeracaoSequencial();
                        }
                    }

                    $juntadasSupp[] = $juntada;
                }
            }
        }

        $juntadasCriadas = [];
        $ordemDosDocumentosVinculados = [];
        usort($metadadosProcesso->documento, fn($a, $b) => $a->ordem <=> $b->ordem);
        foreach ($metadadosProcesso->documento as $documentoTramitacao) {
            $juntadaExistente = false;

            $vinculacoesDoDocumento = $this->getVinculacoesDoDocumento($documentoTramitacao);
            if (count($vinculacoesDoDocumento) > 0) {
                $ordemDosDocumentosVinculados[$documentoTramitacao->ordem] = $vinculacoesDoDocumento;
            }

            /* @var $juntadasSupp [] Juntada */
            foreach ($juntadasSupp as $juntadaSupp) {
                // para processos ja existentes na base
                if ($processo->getOrigemDados()?->getServico() === 'barramento_existente') {
                    if ($juntadaSupp->getNumeracaoSequencial() === $inicio_sinc_processo_existente) {
                        $inicio_sinc_processo_existente++;
                        $juntadaExistente = $juntadaSupp;
                        break;
                    }
                } elseif ($documentoTramitacao->ordem == $juntadaSupp->getNumeracaoSequencial()) {
                    $juntadaExistente = $juntadaSupp;
                    break;
                }
            }

            if ($juntadaExistente) {
                if (isset($documentoTramitacao->retirado) &&
                    true === $documentoTramitacao->retirado &&
                    true === $juntadaExistente->getAtivo()
                ) {
                    $this->cancelaJuntada($juntadaExistente);
                }
                continue;
            }

            // se o documento vem de processo anexado no sei, cria o processo e faz vinculacao por anexacao
            if (isset($documentoTramitacao->protocoloDoProcessoAnexado)) {
                if (!$this->processoResource->findOneBy(['NUP' =>
                    preg_replace('/\D/', '', $documentoTramitacao->protocoloDoProcessoAnexado)])) {
                    $juntadasCriadas[] = $this->criaProcessoAnexo($volume, $documentoTramitacao, $processo, $nre);
                }
            } else {
                $juntadasCriadas[] = $this->criaDocumento(
                    $processo,
                    $volume,
                    $documentoTramitacao,
                    $nre
                );
            }
        }

        $juntadas = array_merge($juntadasCriadas, $juntadasSupp);

        $this->acrescentaVinculacoes($juntadas, $ordemDosDocumentosVinculados);

        // desativado temporariamente por deficiencia do SEI
        //$this->removeVinculacoes($juntadas, $ordemDosDocumentosVinculados);
    }

    /**
     * Verifica se no documento do barramento há vinculação entre outros documentos e retorna as vinculações em
     * formato de array.
     *
     * @param $documentoTramitacao
     */
    private function getVinculacoesDoDocumento($documentoTramitacao): array
    {
        $ordensVinculadas = [];
        $referencias = $this->getValueIfExist($documentoTramitacao, 'ordemDoDocumentoReferenciado');
        if ($referencias) {
            if (!is_array($referencias)) {
                $referencias = [
                    $referencias,
                ];
            }

            foreach ($referencias as $referencia) {
                $ordensVinculadas[] = $referencia;
            }
        }

        return $ordensVinculadas;
    }

    /**
     * Acrescenta as vinculações de documentos oriundas do barramento.
     *
     * @param array $juntadas         Array de juntadas da processo
     * @param array $ordensVinculadas - Array onde o indice a ordem do documento oriundo do barramento
     *                                e o valor é um array cujo valores é a ordem de cada documento vinculado
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function acrescentaVinculacoes(array $juntadas, array $ordensVinculadas)
    {
        $tempoExecucao = microtime(true);
        $documentoPrincipal = '';
        foreach ($ordensVinculadas as $indiceOrdemDocumento => $ordemDocumento) {
            /** @var Juntada $juntada */
            foreach ($juntadas as $juntada) {
                if ($juntada->getNumeracaoSequencial() == $indiceOrdemDocumento) {
                    $documentoPrincipal = $juntada->getDocumento();
                }
            }

            $documentoVinculado = '';
            $existeVinculacao = false;

            foreach ($ordemDocumento as $ordemDocumentoVinculado) {
                /** @var Juntada $juntada */
                foreach ($juntadas as $juntada) {
                    if ($juntada->getNumeracaoSequencial() == $ordemDocumentoVinculado) {
                        $documentoVinculado = $juntada->getDocumento();

                        $vinculacao = $this->vinculacaoDocumentoResource
                            ->findOneBy(['documentoVinculado' => $documentoVinculado->getId()]);

                        if ($vinculacao) {
                            $existeVinculacao = true;
                            break;
                        }
                    }
                }

                if ($existeVinculacao) {
                    $this->logger->critical(
                        'Já existe a vinculação entre as juntadas sequencial '.
                        "$indiceOrdemDocumento e $ordemDocumentoVinculado."
                    );
                    continue;
                }

                $vinculacaoDTO = new VinculacaoDocumentoDto();
                $vinculacaoDTO->setDocumento($documentoPrincipal);
                $vinculacaoDTO->setDocumentoVinculado($documentoVinculado);

                $vinculacaoDTO->setModalidadeVinculacaoDocumento(
                    $this->modalidadeVinculacaoDocumentoResource->findOneBy(['valor' => 'ANEXO'])
                );

                $this->logger->info(
                    'Vinculação do tipo ANEXO criada entre as juntadas de sequencial '.
                    "$indiceOrdemDocumento (principal) e $ordemDocumentoVinculado (vinculada) no ".
                    "processo Id {$documentoPrincipal->getProcessoOrigem()->getId()}."
                );

                $this->vinculacaoDocumentoResource->create($vinculacaoDTO, $this->transactionId);
            }
        }

        $this->logger->info((string)(microtime(true) - $tempoExecucao));
    }

    //   /**
//    * Remove as vinculações de documentos que existem no Supp, mas deixaram de existir no barramento
//    *
//    * @param $juntadas array - Array de juntadas da processo
//    * @param $ordensVinculadas array - Array onde o indice a ordem do documento oriundo do barramento e o valor é
//    * um array cujo valores é a ordem de cada documento vinculado
//    * @throws \Exception
//    */
//    private function removeVinculacoes($juntadas, $ordensVinculadas)
//    {
//        $removeVinculacao = false;
//        /* @var $juntada Juntada */
//        foreach ($juntadas as $juntada) {
//
//            $vinculacoes = $this->vinculacaoDocumentoResource->find(['documento'=>$juntada->getDocumentoJuntado()]);
//
//            /* @var $vinculacao VinculacaoDocumento */
//            foreach ($vinculacoes as $vinculacao) {
//                $documentoVinculadoId = $vinculacao->getDocumentoVinculado()->getId();
//                /* @var $juntadaVinculada Juntada */
//                foreach ($juntadas as $juntadaVinculada) {
//                    if ($juntadaVinculada->getDocumentoJuntado()->getId() == $documentoVinculadoId) {
//                        break;
//                    }
//                }
//
//                if (isset($ordensVinculadas[$juntada->getNumeracaoSequencial()])) {
//                    $existeVinculacaoBarramento = array_search(
//                        $juntadaVinculada->getNumeracaoSequencial(),
//                        $ordensVinculadas[$juntada->getNumeracaoSequencial()]
//                    );
//
//                    if ($existeVinculacaoBarramento === false) {
//                        $removeVinculacao = true;
//                    }
//                } else {
//                    $removeVinculacao = true;
//                }
//
//                if ($removeVinculacao) {
//                    $juntadaVinculada->setVinculada(false);
//                    $this->toPersistEntities[] = $vinculacao;
//
//                    $vinculacao->setApagadoEm(new \DateTime());
//                    $this->toPersistEntities[] = $vinculacao;
//
//                    $this->logger->info("Vinculação de Documento Id {$vinculacao->getId()} removida, pois deixou " .
//                        "de existir no barramento.");
//                }
//            }
//        }
//    }

    /**
     * Cria um novo documento a partir dos objetos gerados por meio dos metadados rebidos pelo barramento.
     *
     * @param $documentoTramitacao
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function criaDocumento(
        Processo $processo,
        $volumeEntity,
        $documentoTramitacao,
        string $nre,
        Juntada $juntadaAnexo = null
    ): Juntada {
        if (isset($this->mapTipoDocumento[$documentoTramitacao->especie->codigo])) {
            $tipoDocumento = $this->tipoDocumentoResource
                ->findOneBy(
                    ['nome' => $this->upperUtf8($this->mapTipoDocumento[$documentoTramitacao->especie->codigo])]
                );
        } else {
            $tipoDocumento = $this->tipoDocumentoResource
                ->findOneBy(['nome' => $this->upperUtf8($documentoTramitacao->especie->nomeNoProdutor)]);
        }

        if (!$tipoDocumento) {
            $tipoDocumento = $this->tipoDocumentoRepository->findByNomeAndEspecie('OUTROS', 'ADMINISTRATIVO');
            $this->logger->info('Tipo de Documento não encontrado: '.''.$documentoTramitacao->especie->nomeNoProdutor);
        }

        /*
         * validação de extensão e tamanho desabilitado para ser mais flexivel
         * recebimento de componentes vindos do barramento
         *
        if (!$documentoTramitacao->retirado) {
            // Antes de criar o documento validamos se a extensao é valida
            $componenteDigitalConfig = $this->parameterBag->get(
                'supp_core.administrativo_backend.componente_digital_extensions'
            );

            $extensao = strtolower(pathinfo($documentoTramitacao->componenteDigital->nome, PATHINFO_EXTENSION));

            if (!$extensao || !isset($componenteDigitalConfig[$extensao])) {
                throw new Exception('O processo possui documento(s) com extensão inválida!');
            }

            if ($documentoTramitacao->componenteDigital->tamanhoEmBytes >
                $componenteDigitalConfig[$extensao]['maxSizeBytes']) {
                throw new Exception('O processo possui documento(s) com tamanho superior ao permitido!');
            }
        }
        */

        $documentoDTO = new DocumentoDto();
        $documentoDTO->setProcessoOrigem($processo);
        $documentoDTO->setTipoDocumento($tipoDocumento);
        if (!empty($documentoTramitacao->dataHoraDeProducao)) {
            $documentoDTO->setDataHoraProducao(
                $this->converteDataBarramento($documentoTramitacao->dataHoraDeProducao)
            );
        }
        $documentoDTO->setAutor($documentoTramitacao->produtor->nome);

        if ('OUTROS' == $tipoDocumento->getNome()) {
            $documentoDTO->setDescricaoOutros($this->upperUtf8(
                $documentoTramitacao->especie->codigo.'_'.$documentoTramitacao->especie->nomeNoProdutor
            ));
        }

        // adiciona restricao de acesso, caso necessario
        if (isset($documentoTramitacao->nivelDeSigilo) && 1 !== $documentoTramitacao->nivelDeSigilo) {
            $documentoDTO->setAcessoRestrito(true);
        }

        $origemDadosDTO = $this->origemDadosFactory();
        $origemDadosDTO->setIdExterno($nre);
        $origemDadosDTO->setStatus(AbstractBarramentoManager::EM_SINCRONIZACAO);
        $origemDadosDocumentoEntity = $this->origemDadosResource->create($origemDadosDTO, $this->transactionId);

        $documentoDTO->setOrigemDados($origemDadosDocumentoEntity);
        if ($juntadaAnexo) {
            // guarda o numero do processo anexo para ser utilizado no envio
            if ($juntadaAnexo->getVolume()->getProcesso()->getOutroNumero() &&
                'BARRAMENTO_PEN' === $juntadaAnexo->getVolume()->getProcesso()
                    ->getOrigemDados()?->getFonteDados()) {
                $documentoDTO->setOutroNumero($juntadaAnexo->getVolume()->getProcesso()->getOutroNumero());
            } else {
                $documentoDTO->setOutroNumero($juntadaAnexo->getVolume()->getProcesso()->getNUPFormatado());
            }
        }
        $documentoEntity = $this->documentoResource->create($documentoDTO, $this->transactionId);

        $juntadaDTO = new JuntadaDto();
        $juntadaDTO->setDocumento($documentoEntity);
        if (!isset($documentoTramitacao->protocoloDoProcessoAnexado)) {
            $juntadaDTO->setNumeracaoSequencial($documentoTramitacao->ordem);
        }
        $juntadaDTO->setVinculada(false);
        $juntadaDTO->setDescricao('DOCUMENTO RECEBIDO VIA INTEGRAÇÃO COM O BARRAMENTO');
        $juntadaDTO->setVolume($volumeEntity);

        // para processos ja existentes na base desconsidera ordem do sei
        if ($processo->getOrigemDados() &&
            $processo->getOrigemDados()->getServico() === 'barramento_existente') {
            $juntadaDTO->setNumeracaoSequencial(null);
        }

        if ($juntadaAnexo) {
            $juntadaDTO->setJuntadaDesentranhada($juntadaAnexo);
            $juntadaDTO->setDescricao($juntadaDTO->getDescricao().
                ' ANEXADO DO NUP n. '.$juntadaAnexo->getVolume()->getProcesso()->getNUPFormatado());
        }

        $origemJuntadaDTO = $this->origemDadosFactory();
        $origemJuntadaDTO->setIdExterno($nre);
        $origemJuntadaDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosJuntadaEntity = $this->origemDadosResource->create($origemJuntadaDTO, $this->transactionId);

        $juntadaDTO->setOrigemDados($origemDadosJuntadaEntity);

        // Verifica se o documento foi cancelado
        if (isset($documentoTramitacao->retirado) && true == $documentoTramitacao->retirado) {
            $juntadaDTO->setAtivo(false);
        }

        if (isset($documentoTramitacao->protocoloDoProcessoAnexado)) {
            $juntadaDTO->setAtivo(false);
        }

        if (!$volumeEntity) {
            $volumeEntity = $this->volumeResource->getRepository()->findVolumeAbertoByProcessoId($processo->getId());
        }
        $juntadaDTO->setVolume($volumeEntity);

        $juntadaEntity = $this->juntadaResource->create($juntadaDTO, $this->transactionId);

        $this->logger->info(
            "Juntada sequencial {$documentoTramitacao->ordem} criada ".
            "no NUP {$processo->getNUPFormatado()}."
        );

        return $juntadaEntity;
    }

    /**
     * Inativa uma juntada de documento para representar o seu cancelamento.
     *
     * @throws Exception - Nenhuma juntada encontrada
     */
    private function cancelaJuntada(Juntada $juntada): void
    {
        if (!$juntada) {
            throw new Exception('Não foi possível inativar a juntada. A juntada não foi localizada');
        }
        /** @var JuntadaDto $juntadaDTO */
        $juntadaDTO = $this->juntadaResource->getDtoForEntity(
            $juntada->getId(),
            JuntadaDTO::class
        );

        $juntadaDTO->setAtivo(false);
        $this->juntadaResource->update($juntada->getId(), $juntadaDTO, $this->transactionId);

        $this->logger->info("Juntada Id {$juntada->getId()} foi inativada nesta sincronização com o barramento.");
    }

    /**
     * Cria o processo anexo e faz a vinculacao junto ao processo principal.
     *
     * @param $documentoTramitacao - documento tramitado pelo barramento
     * @param $processo Processo - processo criado no super
     *
     * @throws \Exception
     */
    private function criaProcessoAnexo($volume, $documentoTramitacao, $processo, $nre)
    {
        $processoAnexo = $this->criaProcesso($documentoTramitacao, $processo, $nre);
        $volumeProcessoAnexo = $processoAnexo->getVolumes()[0];
        if (!$volumeProcessoAnexo) {
            $volumeProcessoAnexo = (new ArrayCollection($this->transactionManager
                ->getScheduledEntities(Volume::class, $this->transactionId)))
                ->filter(fn (Volume $v) => $v->getProcesso()->getUuid() === $processoAnexo->getUuid())
                ->first();
        }

        // cria o documento já desentranhado no processo anexo
        $juntadaDocumentoAnexo = $this->criaDocumento($processoAnexo, $volumeProcessoAnexo, $documentoTramitacao, $nre);

        $vinculacaoProcesso = (new ArrayCollection($this->transactionManager
            ->getScheduledEntities(VinculacaoProcessoEntity::class, $this->transactionId)))
            ->filter(
                fn (VinculacaoProcessoEntity $vp) =>
                    $vp->getProcessoVinculado()->getUuid() === $processoAnexo->getUuid()
            )->first();

        if (!$vinculacaoProcesso) {
            // cria vinculacao dos processos
            $this->criaVinculacaoProcesso($processo, $processoAnexo);
        }

        // chama novamente a criacao de documento para criar o documento no processo principal
        $documentoTramitacaoPrincipal = $documentoTramitacao;
        unset($documentoTramitacaoPrincipal->protocoloDoProcessoAnexado);
        $juntadaDocumento = $this->criaDocumento(
            $processo,
            $volume,
            $documentoTramitacaoPrincipal,
            $nre,
            $juntadaDocumentoAnexo
        );

        return $juntadaDocumento;
    }

    /**
     * Cria o processo anexo.
     *
     * @param $documentoTramitacao - documento tramitado pelo barramento
     * @param $processo Processo - processo criado no super
     *
     * @throws \Exception
     */
    public function criaProcesso($documentoTramitacao, $processo, $nre)
    {
        $protocoloSemFormatacao =
            preg_replace('/\D/', '', $documentoTramitacao->protocoloDoProcessoAnexado);

        /** @var ProcessoEntity $processoEntity */
        $processoEntity = $this->processoResource->findOneBy(['NUP' => $protocoloSemFormatacao]);

        if (!$processoEntity) {
            // verifica se o processo já existe pelo atributo outroNumero
            $processoEntity = $this->processoResource->findOneBy(
                ['outroNumero' => $documentoTramitacao->protocoloDoProcessoAnexado]
            );

            if ($processoEntity && $processoEntity->getOrigemDados() &&
                'BARRAMENTO_PEN' === $processoEntity->getOrigemDados()->getFonteDados()) {
                return $processoEntity;
            } else {
                $processoEntity = null;
            }
        }

        if (!$processoEntity) {
            $processoEntity = (new ArrayCollection($this->transactionManager
                ->getScheduledEntities(Processo::class, $this->transactionId)))
                ->filter(fn (Processo $p) => $p->getNUP() === $protocoloSemFormatacao)
                ->first();

            if (!$processoEntity) {
                $processoEntity = (new ArrayCollection($this->transactionManager
                    ->getScheduledEntities(Processo::class, $this->transactionId)))
                    ->filter(
                        fn (Processo $p) => $p->getOutroNumero() === $documentoTramitacao->protocoloDoProcessoAnexado
                    )
                    ->first();
            }
        }

        if ($processoEntity) {
            return $processoEntity;
        }

        $processoDTO = new ProcessoDTO();
        $processoDTO->setUnidadeArquivistica(ProcessoEntity::UA_PROCESSO);
        $processoDTO->setTipoProtocolo(ProcessoEntity::TP_INFORMADO);
        $processoDTO->setNUP(
            preg_replace('/\D/', '', $documentoTramitacao->protocoloDoProcessoAnexado)
        );
        $processoDTO->setClassificacao(
            $this->classificacaoResource->findOneBy(['codigo' => '069'])
        );
        $processoDTO->setEspecieProcesso(
            $this->especieProcessoRepository->findOneBy(['nome' => 'COMUM'])
        );
        $processoDTO->setModalidadeMeio(
            $this->modalidadeMeioResource->findOneBy(['valor' => 'ELETRÔNICO'])
        );

        $processoDTO->setTitulo(
            'PROCESSO RECEBIDO VIA INTEGRAÇÃO COM O BARRAMENTO ANEXO AO NUP '
                .$processo->getNUPFormatado()
        );
        $processoDTO->setProcedencia($processo->getProcedencia());
        $processoDTO->setSetorAtual($processo->getSetorAtual());

        // verifica Nup valido
        $validarNup = $this->nupProviderManager->getNupProvider(
            $processoDTO
        )->validarNumeroUnicoProtocolo($processoDTO);

        // caso numero nup for invalido
        if (!$validarNup) {
            $processoDTO->setTipoProtocolo(ProcessoEntity::TP_NOVO);
            $processoDTO->setOutroNumero($documentoTramitacao->protocoloDoProcessoAnexado);
            $processoDTO->setNUP(
                $this->nupProviderManager->getNupProvider($processoDTO)->gerarNumeroUnicoProtocolo($processoDTO)
            );
        }

        /** @var OrigemDadosDTO $origemDadosDTo */
        $origemDadosDTo = $this->origemDadosFactory();
        $origemDadosDTo->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosDTo->setIdExterno($nre);

        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTo, $this->transactionId);
        $processoDTO->setOrigemDados($origemDadosEntity);

        $processoAnexo = $this->processoResource->create($processoDTO, $this->transactionId);

        return $processoAnexo;
    }

    /**
     * @param $processo
     * @param $processoAnexo
     *
     * @return VinculacaoProcessoEntity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function criaVinculacaoProcesso($processo, $processoAnexo)
    {
        $vinculacaoProcessoDTO = new VinculacaoProcesso();
        $vinculacaoProcessoDTO->setProcesso($processo);
        $vinculacaoProcessoDTO->setProcessoVinculado($processoAnexo);
        $vinculacaoProcessoDTO->setModalidadeVinculacaoProcesso(
            $this->modalidadeVinculacaoProcessoRepository->findOneBy(['valor' => 'ANEXAÇÃO'])
        );

        return $this->vinculacaoProcessoResource
            ->create($vinculacaoProcessoDTO, $this->transactionId);
    }

    /**
     * @param $processo
     * @return void
     */
    public function criaTermoSincronizacao($processo, $metadadosProcesso, $transactionId)
    {
        $documentoDTO = new DocumentoDto();
        $documentoDTO->setProcessoOrigem($processo);
        $documentoDTO->setTipoDocumento(
            $this->tipoDocumentoRepository->findByNomeAndEspecie('OUTROS', 'ADMINISTRATIVO')
        );
        $documentoEntity = $this->documentoResource->create($documentoDTO, $transactionId);

        $template = 'Resources/Barramento/termo_sincronizacao.html.twig';
        $conteudoHTML = $this->twig->render(
            $template,
            [
                'nup' => $processo->getNUPFormatado(),
                'titulo' => strtoupper($metadadosProcesso->descricao),
                'data' => $metadadosProcesso->dataHoraDeProducao ?
                        $this->converteDataBarramento($metadadosProcesso->dataHoraDeProducao) : new \DateTime()
            ]
        );

        // cria o componente Digital
        $componenteDigitalDTO = new ComponenteDigital();
        $componenteDigitalDTO->setConteudo($conteudoHTML);
        $componenteDigitalDTO->setMimetype('text/html');
        $componenteDigitalDTO->setExtensao('html');
        $componenteDigitalDTO->setEditavel(false);
        $componenteDigitalDTO->setFileName('termo_sincronizacao.html');
        $componenteDigitalDTO->setTamanho(strlen($conteudoHTML));
        $componenteDigitalDTO->setNivelComposicao(3);
        $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setTamanho(strlen($componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setDocumento($documentoEntity);
        $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

        $juntadaDTO = new JuntadaDto();
        $juntadaDTO->setDocumento($documentoEntity);
        $juntadaDTO->setDescricao('TERMO DE INÍCIO DA SINCRONIZAÇÃO COM O BARRAMENTO');
        $this->juntadaResource->create($juntadaDTO, $transactionId);
    }
}
