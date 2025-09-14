<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use ReflectionException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\CriaTarefaBarramentoMessage;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por realizar o recebimento de componentes digitais pelo Barramento.
 */
class BarramentoRecebeComponenteDigital extends AbstractBarramentoManager
{
    /**
     * ID da tramitação do Barramento PEN.
     */
    private int $idt;

    private string $transactionId;

    /**
     * Serviço de sincronização de objetos entre o barramento e o Sapiens.
     */
    private BarramentoSincronizacaoComponenteDigital $sincronizacao;

    /**
     * Data e hora em que o último componente digital do barramento foi baixado para envio do trâmite.
     */
    private ?DateTime $dtHrUltimoComponenteBaixado = null;

    private ProcessoResource $processoResource;

    private SetorResource $setorResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private OrigemDadosResource $origemDadosResource;

    private JuntadaResource $juntadaResource;

    private JuntadaRepository $juntadaRepository;

    private ComponenteDigitalRepository $componenteDigitalRepository;

    protected array $config;

    /**
     * Armazena id dos componentes digitais que serão indexados.
     */
    private array $componentesDigitaisParaIndexar = [];

    private array $hashesRecebidos = [];

    private TransactionManager $transactionManager;



    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        SetorResource $setorResource,
        OrigemDadosResource $origemDadosResource,
        BarramentoSincronizacaoComponenteDigital $sincronizacao,
        StatusBarramentoResource $statusBarramentoResource,
        JuntadaResource $juntadaResource,
        JuntadaRepository $juntadaRepository,
        ComponenteDigitalRepository $componenteDigitalRepository,
        TransactionManager $transactionManager,
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->logger = $logger;
        $this->client = $barramentoClient;
        $this->processoResource = $processoResource;
        $this->setorResource = $setorResource;
        $this->sincronizacao = $sincronizacao;
        $this->origemDadosResource = $origemDadosResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->juntadaResource = $juntadaResource;
        $this->juntadaRepository = $juntadaRepository;
        $this->componenteDigitalRepository = $componenteDigitalRepository;
        $this->transactionManager = $transactionManager;

        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
    }

    /**
     * Recebe o conteúdo binário de cada componente digital tramitado. Após o recebimento do último, fica
     * permitido ao destinatário o envio do recebimento de trâmite, processo necessário para efetivação e
     * conclusão do fluxo.
     *
     * @param int $idt - Ticket do barramento (StatusBarramentoResource - idt)
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function receberComponentesDigitais(int $idt, string $transactionId)
    {
        $this->idt = $idt;
        $this->transactionId = $transactionId;

        $params = new stdClass();
        $params->IDT = $this->idt;

        $this->hashesRecebidos = [];

        $this->response = $this->client->solicitarMetadados($params);

        if (!isset($this->response->metadados)) {
            throw new Exception("Não foi possível obter os dados do trâmite de número: 
            $this->idt. {$this->getMensagemErro()}");
        }

        /** @var StatusBarramento $statusBarramentoEntity */
        $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(['idt' => $this->idt]);

        if ($statusBarramentoEntity && $statusBarramentoEntity->getCodSituacaoTramitacao()) {
            $objetoComponenteDigital = $statusBarramentoEntity->getDocumentoAvulso() ?:
                    $statusBarramentoEntity->getProcesso();

            if ($objetoComponenteDigital instanceof DocumentoAvulsoEntity) {
                $this->receberComponentesDoDocumentoAvulso(
                    $this->response->metadados->documento,
                    $objetoComponenteDigital
                );
            }

            if ($objetoComponenteDigital instanceof ProcessoEntity) {
                if (isset($this->response->metadados->processo)) {
                    $this->receberComponentesDoProcesso($this->response->metadados->processo);
                } elseif(isset($this->response->metadados->documento)) {
                    $this->receberComponentesDoOficio($this->response->metadados->documento);
                }

                $processosApensados = $this->getValueIfExist($this->response->metadados, 'processoApensado');

                if ($processosApensados) {
                    if (!is_array($processosApensados)) {
                        $processosApensados = [$processosApensados];
                    }

                    foreach ($processosApensados as $processoApensado) {
                        $this->receberComponentesDoProcesso($processoApensado);
                    }
                }
            }

            if (3 === $statusBarramentoEntity->getCodSituacaoTramitacao()) {
                // atualiza status da tramitacao
                $this->atualizaStatusTramitacao($statusBarramentoEntity, self::SIT_DOC_PROC_RECEBIDO_DESTINATARIO);
            }
        }

        if (!$statusBarramentoEntity) {
            throw new Exception("Não foi encontrar o status do tramite com o idt: $this->idt");
        }

        // validando caso seja fila repetida
        if (self::SIT_RECIBO_CONCLUSAO_RECEBIDO_BARRAMENTO !== $statusBarramentoEntity->getCodSituacaoTramitacao()) {
            $responseRecibo = $this->enviaReciboDeTramite($this->response->metadados);

            $protocoloSemFormatacao = preg_replace('/\D/', '', $this->response->metadados->processo->protocolo);


            // Cria tarefa depois de receber os componente digitais.
            $consultarTramitesResponse = $this->client->consultarTramites($this->idt);

            $setorDestinatario = $this->repositorioToSetor(
                $consultarTramitesResponse->tramitesEncontrados->tramite
                    ->destinatario->identificacaoDoRepositorioDeEstruturas,
                $consultarTramitesResponse->tramitesEncontrados->tramite->destinatario->numeroDeIdentificacaoDaEstrutura
            );

            if (!$setorDestinatario) {
                throw new Exception('Setor destino do trâmite não existe.');
            }

            $protocolo = $this->setorResource->getRepository()
                ->findProtocoloInUnidade($setorDestinatario->getId());

            $setorResponsavel = $protocolo ?? $setorDestinatario;

            /** @var ProcessoEntity $processoEntity */
            $processoEntity = $this->processoResource->findOneBy(['NUP' => $protocoloSemFormatacao]);

            $tarefaBarramentoMessage = new CriaTarefaBarramentoMessage();
            $tarefaBarramentoMessage->setProcessoUuid($processoEntity->getUuid());
            $tarefaBarramentoMessage->setSetorResponsavelId($setorResponsavel->getId());

            $this->transactionManager->addAsyncDispatch($tarefaBarramentoMessage, $this->transactionId);
            /// Cria tarefa depois de receber os componente digitais.

            if ($responseRecibo) {
                $this->atualizaStatusTramitacao(
                    $statusBarramentoEntity,
                    self::SIT_RECIBO_CONCLUSAO_RECEBIDO_BARRAMENTO
                );
            }
        }

        return true;
    }


    /**
     * Converte um repositório do barramento em objeto Setor.
     *
     * @return bool|SetorEntity
     */
    private function repositorioToSetor(
        int $identificacaoDoRepositorioDeEstruturas,
        string $numeroDeIdentificacaoDaEstrutura
    ) {
        $consultarEstruturasResponse = $this->client->consultarEstruturas(
            $identificacaoDoRepositorioDeEstruturas,
            (int) $numeroDeIdentificacaoDaEstrutura
        );

        /** @var SetorEntity $setorEntity */
        $setorEntity = $this->setorResource->findOneBy(
            ['id' => (int) $consultarEstruturasResponse->estruturasEncontradas->estrutura->codigoNoOrgaoEntidade]
        );

        if (!$setorEntity) {
            $setorEntity = $this->setorResource->findOneBy(
                ['sigla' => $this->upperUtf8($consultarEstruturasResponse->estruturasEncontradas->estrutura->sigla)]
            );
        }

        return $setorEntity;
    }

    /**
     * Recebe componentes digitais do processo informado.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    private function receberComponentesDoDocumentoAvulso(
        stdClass $metadadosDocumento,
        DocumentoAvulsoEntity $documentoAvulsoEntity
    ): void {
        if (isset($metadadosDocumento->retirado) && $metadadosDocumento->retirado) {
            return;
        }

        $documento = $documentoAvulsoEntity->getDocumentoResposta() ?? $documentoAvulsoEntity->getDocumentoRemessa();

        if (!is_array($metadadosDocumento->componenteDigital)) {
            $metadadosDocumento->componenteDigital = [$metadadosDocumento->componenteDigital];
        }

        foreach ($metadadosDocumento->componenteDigital as $componenteDigitalTramitacao) {
            if (!$componenteDigitalTramitacao->retirado) {
                try {
                    $conteudo = $this->receberComponenteDigital(
                        $metadadosDocumento->protocolo,
                        $componenteDigitalTramitacao->hash->_,
                        $componenteDigitalTramitacao->tamanhoEmBytes
                    );
                    if($conteudo) {
                        $this->hashesRecebidos[] = $componenteDigitalTramitacao->hash->_;
                    } else {
                        throw new \Exception('Não foi possível obter o conteúdo do componente digital');
                    }
                } catch (Exception $e) {
                    $this->logger->critical(
                        'Não foi possível obter o conteúdo do componente digital'.
                        " [{$componenteDigitalTramitacao->hash->_}]. Erro: {$e->getMessage()}"
                    );
                }
            } else {
                $conteudo = 'DOCUMENTO RETIRADO';
            }

            $this->dtHrUltimoComponenteBaixado = new DateTime();

            $componenteDigital = $this->sincronizacao->sincronizaComponenteDigital(
                $conteudo,
                $documento,
                $componenteDigitalTramitacao,
                $this->transactionId
            );

            $this->componentesDigitaisParaIndexar[] = $componenteDigital->getId();
        }

        //atualiza origem dados documento
        /* @var OrigemDadosDTO $origemDadosDTO */
        if ($documento->getOrigemDados()) {
            $origemDadosDTO = $this->origemDadosResource
                ->getDtoForEntity($documento->getOrigemDados()->getId(), OrigemDadosDTO::class);
            $origemDadosDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
            $this->origemDadosResource
                ->update($documento->getOrigemDados()->getId(), $origemDadosDTO, $this->transactionId);
        }
    }

    /**
     * Recebe o conteúdo binário de cada componente digital tramitado. Após o recebimento do último, fica
     * permitido ao destinatário o envio do recebimento de trâmite, processo necessário para efetivação e
     * conclusão do fluxo.
     *
     * @throws Exception Não há componentes digitais
     */
    private function receberComponenteDigital(string $protocolo, string $hash, int $tamanhoComponente = null): mixed
    {
        $params = new stdClass();
        $params->parametrosParaRecebimentoDeComponenteDigital = new stdClass();
        $params->parametrosParaRecebimentoDeComponenteDigital->identificacaoDoComponenteDigital = new stdClass();
        $params->parametrosParaRecebimentoDeComponenteDigital->identificacaoDoComponenteDigital->IDT = $this->idt;
        $params->parametrosParaRecebimentoDeComponenteDigital->identificacaoDoComponenteDigital->protocolo = $protocolo;
        $params->parametrosParaRecebimentoDeComponenteDigital->identificacaoDoComponenteDigital
            ->hashDoComponenteDigital = $hash;

        $inicio = 0;
        $batch = 52428800;
        $conteudo = null;

        if ($tamanhoComponente > $batch) {
            $limiteBytes = $batch;
            $params->parametrosParaRecebimentoDeComponenteDigital->parte = new stdClass();

            // Busca componentes de 50 em 50MB
            while ($tamanhoComponente > $inicio) {
                if (($tamanhoComponente - $inicio) < $batch) {
                    $limiteBytes = $tamanhoComponente;
                }
                $params->parametrosParaRecebimentoDeComponenteDigital->parte->inicio = $inicio;
                $params->parametrosParaRecebimentoDeComponenteDigital->parte->fim = $limiteBytes;
                $inicio = $limiteBytes;
                $limiteBytes += $batch;
                $responseClient = $this->client->receberComponenteDigital($params);
                $conteudo .= $responseClient->conteudoDoComponenteDigital;
            }
        } else {
            $responseClient = $this->client->receberComponenteDigital($params);
            $conteudo = $responseClient->conteudoDoComponenteDigital;
        }

        if (!$conteudo) {
            throw new Exception("Não foi possível obter o componente digital do idt: 
            $this->idt. {$this->getMensagemErro()}");
        }

        return $conteudo;
    }

    /**
     * Recebe componentes digitais do processo informado.
     *
     * @throws Exception - Processo não encontratada
     */
    private function receberComponentesDoProcesso(stdClass $metadadosProcesso): void
    {
        $protocoloSemFormatacao = preg_replace('/\D/', '', $metadadosProcesso->protocolo);

        $processoEntity = $this->processoResource->findOneBy(
            ['NUP' => $protocoloSemFormatacao]
        );

        if (!$processoEntity) {
            // caso seja de um numero invalido o numero do nup esta no atributo outroNumero
            $processoEntity = $this->processoResource->findOneBy(
                ['outroNumero' => $metadadosProcesso->protocolo]
            );
            if (!$processoEntity) {
                throw new Exception('Processo não encontrado.');
            }
        }

        // Atualiza status do processo caso tenha dado erro anteriormente
        if ($processoEntity && $processoEntity->getOrigemDados()) {
            $dtoOrigemDadosProcesso = (new OrigemDadosDTO())->setStatus(1);

            $this->origemDadosResource
                ->update($processoEntity->getOrigemDados()->getId(), $dtoOrigemDadosProcesso, $this->transactionId);
        }

        if (!is_array($metadadosProcesso->documento)) {
            $metadadosProcesso->documento = [$metadadosProcesso->documento];
        }

        $juntadaEntityArray = $this->juntadaRepository->findJuntadaByProcesso($processoEntity);

        $this->sincronizaComponentesDigitaisDoProcesso($metadadosProcesso, $juntadaEntityArray, $processoEntity);
    }

    /**
     * Recebe componentes digitais de oficio.
     *
     * @throws Exception - Processo não encontratada
     */
    private function receberComponentesDoOficio(stdClass $metadadosDocumento): void
    {
        $protocoloSemFormatacao = preg_replace('/\D/', '', $metadadosDocumento->protocolo);

        $processoEntity = $this->processoResource->findOneBy(
            ['NUP' => $protocoloSemFormatacao]
        );

        if (!$processoEntity) {
            // caso seja de um numero invalido o numero do nup esta no atributo outroNumero
            $processoEntity = $this->processoResource->findOneBy(
                ['outroNumero' => $metadadosDocumento->protocolo]
            );
            if (!$processoEntity) {
                throw new Exception('Processo não encontrado.');
            }
        }

        // Atualiza status do processo caso tenha dado erro anteriormente
        if ($processoEntity && $processoEntity->getOrigemDados()) {
            $dtoOrigemDadosProcesso = (new OrigemDadosDTO())->setStatus(1);

            $this->origemDadosResource
                ->update($processoEntity->getOrigemDados()->getId(), $dtoOrigemDadosProcesso, $this->transactionId);
        }
        $metadadosDocumento->documento = [$metadadosDocumento];


        $juntadaEntityArray = $this->juntadaRepository->findJuntadaByProcesso($processoEntity);

        $this->sincronizaComponentesDigitaisDoOficio($metadadosDocumento, $juntadaEntityArray, $processoEntity);
    }

    /**
     * Sincroniza componentes digitais de todos os documentos.
     *
     * @param JuntadaEntity[] $juntadaEntityArray
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function sincronizaComponentesDigitaisDoProcesso(
        stdClass $metadadosProcesso,
        array $juntadaEntityArray,
        ProcessoEntity $processoEntity
    ): void {
        usort($metadadosProcesso->documento, fn($a, $b) => $a->ordem <=> $b->ordem);
        $inicio_sinc_processo_existente = 0;
        foreach ($metadadosProcesso->documento as $documentoTramitacao) {
            $documentoEntity = false;
            foreach ($juntadaEntityArray as $juntadaEntity) {
                // processo existente na base
                if ($processoEntity->getOrigemDados()?->getServico() === 'barramento_existente') {
                    if (!$inicio_sinc_processo_existente && !$juntadaEntity->getOrigemDados()) {
                        continue;
                    } elseif (!$inicio_sinc_processo_existente) {
                        $inicio_sinc_processo_existente = $juntadaEntity->getNumeracaoSequencial();
                    }
                    if ($juntadaEntity->getNumeracaoSequencial() === $inicio_sinc_processo_existente) {
                        $inicio_sinc_processo_existente++;
                        $documentoEntity = $juntadaEntity->getDocumento();
                        break;
                    }
                } elseif ($juntadaEntity->getNumeracaoSequencial() === $documentoTramitacao->ordem) {
                    /* @var DocumentoEntity $documentoEntity */
                    $documentoEntity = $juntadaEntity->getDocumento();
                    break;
                }
            }

            if (!$documentoEntity) {
                $this->logger->info("Documento de ordem {$documentoTramitacao->ordem} não foi recebido".
                    ' pela integração do barramento PEN.');
                continue;
            }

            if (!is_array($documentoTramitacao->componenteDigital)) {
                $documentoTramitacao->componenteDigital = [$documentoTramitacao->componenteDigital];
            }

            foreach ($documentoTramitacao->componenteDigital as $componenteDigitalTramitacao) {
                if (!$documentoTramitacao->retirado) {
                    try {
                        $conteudo = $this->receberComponenteDigital(
                            $metadadosProcesso->protocolo,
                            $componenteDigitalTramitacao->hash->_,
                            $componenteDigitalTramitacao->tamanhoEmBytes
                        );

                        $this->hashesRecebidos[] = $componenteDigitalTramitacao->hash->_;
                        $this->dtHrUltimoComponenteBaixado = new DateTime();

                        if (!$documentoEntity->getComponentesDigitais()->isEmpty()) {
                            continue;
                        }

                        if (!$conteudo) {
                            throw new \Exception('Não foi possível obter o conteúdo do componente digital');
                        }
                    } catch (Exception $e) {
                        $this->logger->critical(
                            'Não foi possível obter o conteúdo do componente digital'.
                            " [{$componenteDigitalTramitacao->hash->_}]. Erro: {$e->getMessage()}"
                        );

                        if ($documentoEntity->getComponentesDigitais()->isEmpty())
                            throw new \Exception('Não foi possível obter o conteúdo do componente digital');

                        continue;
                    }
                } else {
                    $conteudo = 'DOCUMENTO RETIRADO';
                }

                $this->componentesDigitaisParaIndexar[] = $this->sincronizacao->sincronizaComponenteDigital(
                    $conteudo,
                    $documentoEntity,
                    $componenteDigitalTramitacao,
                    $this->transactionId
                );

                //valida se esse hash de documento é de ofício remetido sem recebimento
                if ($documentoTramitacao->ordem === 1 &&
                    $documentoEntity->getOrigemDados() &&
                    $documentoEntity->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN' &&
                    $processoEntity->getOrigemDados()?->getServico() !== 'barramento_existente') {
                    $this->sincronizacao->searchOficioFromHash(hash('SHA256', $conteudo), $documentoEntity);
                }
            }

            //atualiza origem dados documento
            /* @var OrigemDadosDTO $origemDadosDTO */
            if ($documentoEntity->getOrigemDados() && $documentoEntity->getOrigemDados()->getId()) {
                $origemDadosDTO = $this->origemDadosResource
                    ->getDtoForEntity($documentoEntity->getOrigemDados()->getId(), OrigemDadosDTO::class);
                $origemDadosDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
                $this->origemDadosResource
                    ->update($documentoEntity->getOrigemDados()->getId(), $origemDadosDTO, $this->transactionId);
            }
        }
    }

    private function sincronizaComponentesDigitaisDoOficio(
        stdClass $metadadosDocumento,
        array $juntadaEntityArray,
        ProcessoEntity $processoEntity
    ): void {
        foreach ($metadadosDocumento->documento as $documentoTramitacao) {
            foreach ($juntadaEntityArray as $juntadaEntity) {
                $documentoEntity = $juntadaEntity->getDocumento();

                $conteudo = $this->receberComponenteDigital(
                    $documentoTramitacao->protocolo,
                    $documentoTramitacao->componenteDigital->hash->_,
                    $documentoTramitacao->componenteDigital->tamanhoEmBytes
                );
                $this->hashesRecebidos[] = $documentoTramitacao->componenteDigital->hash->_;

                $this->dtHrUltimoComponenteBaixado = new DateTime();

                $this->componentesDigitaisParaIndexar[] = $this->sincronizacao->sincronizaComponenteDigital(
                    $conteudo,
                    $documentoEntity,
                    $documentoTramitacao->componenteDigital,
                    $this->transactionId
                );

                //atualiza origem dados documento
                /* @var OrigemDadosDTO $origemDadosDTO */
                if ($documentoEntity->getOrigemDados() && $documentoEntity->getOrigemDados()->getId()) {
                    $origemDadosDTO = $this->origemDadosResource
                        ->getDtoForEntity($documentoEntity->getOrigemDados()->getId(), OrigemDadosDTO::class);
                    $origemDadosDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
                    $this->origemDadosResource
                        ->update($documentoEntity->getOrigemDados()->getId(), $origemDadosDTO, $this->transactionId);
                }
            }
        }
    }

    /**
     * Realiza envio do recibo de trâmite ao barramento.
     *
     * @param stdClass $metadados - Metadados do processo
     *
     * @return mixed
     *
     * @throws Exception
     */
    private function enviaReciboDeTramite(stdClass $metadados)
    {
        date_default_timezone_set('America/Sao_Paulo');

        if (null === $this->dtHrUltimoComponenteBaixado) {
            $this->dtHrUltimoComponenteBaixado = new DateTime();
        }

        //$dateTimeFormatado = $this->dtHrUltimoComponenteBaixado->format('Y-m-d\TH:i:s.000\Z');
        $dateTimeFormatado = gmdate("Y-m-d\TH:i:s.000\Z", $this->dtHrUltimoComponenteBaixado->getTimestamp());

        $recibo = '<recibo>';
        $recibo .= "<IDT>$this->idt</IDT>";
        $recibo .= "<NRE>$metadados->NRE</NRE>";
        $recibo .= "<dataDeRecebimento>$dateTimeFormatado</dataDeRecebimento>";

        sort($this->hashesRecebidos);
        $hashes = $this->hashesRecebidos;

        foreach ($hashes as $hash) {
            $recibo .= "<hashDoComponenteDigital>$hash</hashDoComponenteDigital>";
        }

        $recibo .= '</recibo>';

        $hashDaAssinatura = null;
        $certificadoLocal = 'file://'.$this->config['certificado'];
        $pkey = openssl_pkey_get_private($certificadoLocal, $this->config['cert_senha']);

        openssl_sign($recibo, $hashDaAssinatura, $pkey, 'sha256');

        $hashDaAssinatura = base64_encode((string) $hashDaAssinatura);

        $this->logger->info("Metadados do recibo enviado [$recibo]");

        $params = new stdClass();
        $params->dadosDoReciboDeTramite = new stdClass();
        $params->dadosDoReciboDeTramite->IDT = $this->idt;
        $params->dadosDoReciboDeTramite->dataDeRecebimento = $dateTimeFormatado;
        $params->dadosDoReciboDeTramite->hashDaAssinatura = $hashDaAssinatura;

        return $this->client->enviarReciboDeTramite($params);
    }

    /**
     * Analisa response e retorna o protocolo independente do tipo de trâmite.
     *
     * @return mixed
     */
    private function getProtocoloInResponse()
    {
        $protocolo = null;

        if (isset($this->response->metadados)) {
            if (isset($this->response->metadados->documento)) {
                $protocolo = $this->response->metadados->documento->protocolo;
                $protocolo = explode('_', $protocolo);
                $protocolo = $protocolo[0];
            }

            if (isset($this->response->metadados->processo)) {
                $protocolo = $this->response->metadados->processo->protocolo;
            }
        }

        return $protocolo;
    }

    /**
     * Altera o status da sincronização.
     *
     * @throws Exception - Processo não encontratada
     */
    public function alteraStatusSincronizacao(int $status, string $transactionId): void
    {
        if (isset($this->response->metadados->processo)) {
            $metadadosProcesso = $this->response->metadados->processo;

            $protocoloSemFormatacao =
                preg_replace('/\D/', '', $metadadosProcesso->protocolo);

            $processoEntity = $this->processoResource->findOneBy(
                ['NUP' => $protocoloSemFormatacao]
            );

            if ($processoEntity && $processoEntity->getOrigemDados()) {
                $dtoOrigemDadosProcesso = (new OrigemDadosDTO())->setStatus($status);

                $this->origemDadosResource
                    ->update($processoEntity->getOrigemDados()->getId(), $dtoOrigemDadosProcesso, $transactionId);
            }

            foreach ($metadadosProcesso->documento as $documentoTramitacao) {
                if (isset($metadadosProcesso->documento->retirado) && $metadadosProcesso->documento->retirado) {
                    continue;
                }
                $juntadaEntityArray = $this->juntadaRepository->findByProcessoAndFonteDados(
                    $processoEntity,
                    $this->fonteDeDados
                );

                $documentoEntity = false;

                //atualiza origem dados documento
                foreach ($juntadaEntityArray as $juntadaEntity) {
                    if ($juntadaEntity->getNumeracaoSequencial() == $documentoTramitacao->ordem) {
                        /* @var DocumentoEntity $documentoEntity */
                        $documentoEntity = $juntadaEntity->getDocumento();
                        break;
                    }
                }

                /* @var OrigemDadosDTO $origemDadosDTO */
                if ($documentoEntity && $documentoEntity->getOrigemDados() &&
                        $documentoEntity->getOrigemDados()->getId()) {
                    $origemDadosDTO = $this->origemDadosResource
                        ->getDtoForEntity($documentoEntity->getOrigemDados()->getId(), OrigemDadosDTO::class);
                    $origemDadosDTO->setStatus($status);
                    $this->origemDadosResource
                        ->update($documentoEntity->getOrigemDados()->getId(), $origemDadosDTO, $transactionId);
                }
            }
        }
    }

    /**
     * Cria tramitação.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function atualizaStatusTramitacao($statusBarramentoEntity, $status): void
    {
        if ($statusBarramentoEntity) {
            /** @var StatusBarramentoDTO $statusBarramentoDto */
            $statusBarramentoDto = $this->statusBarramentoResource->getDtoForEntity(
                $statusBarramentoEntity->getId(),
                StatusBarramentoDTO::class
            );

            $statusBarramentoDto->setCodSituacaoTramitacao($status);

            $this->statusBarramentoResource->update(
                $statusBarramentoEntity->getId(),
                $statusBarramentoDto,
                $this->transactionId
            );
        }
    }
}
