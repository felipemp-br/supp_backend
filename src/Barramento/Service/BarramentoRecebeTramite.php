<?php

declare(strict_types=1);
/**
 * /src/Service/BarramentoRecebeTramite.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use ReflectionException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaBarramento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeQualificacaoPessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaBarramentoResource;
use SuppCore\AdministrativoBackend\Entity\ModalidadeQualificacaoPessoa as ModalidadeQualificacaoPessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Realiza todas as rotinas de criação e transformação de objetos para enviar e receber tramites pelo barramento.
 */
class BarramentoRecebeTramite extends AbstractBarramentoManager
{
    private string $transactionId;

    private int $idt;

    private ProcessoResource $processoResource;

    private OrigemDadosResource $origemDadosResource;

    /**
     * Serviço de sincronização de objetos entre o barramento e o SUPP.
     */
    private BarramentoSincronizacaoProcesso $sincronizacaoProcesso;

    /**
     * Serviço de sincronização de objetos entre o barramento e o SUPP.
     */
    private BarramentoSincronizacaoDocumento $sincronizacaoDocumento;

    /**
     * Serviço de sincronização de objetos entre o barramento e o SUPP.
     */
    private PessoaResource $pessoaResource;

    private ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource;

    private SetorResource $setorResource;

    private VinculacaoPessoaBarramentoResource $vinculacaoPessoaBarramentoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    protected array $config;

    /**
     * BarramentoRecebeTramite constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        BarramentoSincronizacaoProcesso $sincronizacaoProcesso,
        BarramentoSincronizacaoDocumento $sincronizacaoDocumento,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        PessoaResource $pessoaResource,
        ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource,
        SetorResource $setorResource,
        VinculacaoPessoaBarramentoResource $vinculacaoPessoaBarramentoResource,
        StatusBarramentoResource $statusBarramentoResource
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->sincronizacaoProcesso = $sincronizacaoProcesso;
        $this->sincronizacaoDocumento = $sincronizacaoDocumento;
        $this->processoResource = $processoResource;
        $this->origemDadosResource = $origemDadosResource;
        $this->pessoaResource = $pessoaResource;
        $this->modalidadeQualificacaoPessoaResource = $modalidadeQualificacaoPessoaResource;
        $this->setorResource = $setorResource;
        $this->vinculacaoPessoaBarramentoResource = $vinculacaoPessoaBarramentoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
    }

    /**
     * Recebe trâmite de documento avulso do barramento.
     *
     * @throws Exception
     */
    private function receberDocumento(
        PessoaEntity $pessoaRemetente,
        SetorEntity $setorDestinatario,
        string $nre
    ): ProcessoEntity {
        return $this->sincronizacaoDocumento->sincronizaDocumentoAvulso(
            $this->idt,
            $this->response->metadados->documento,
            $pessoaRemetente,
            $setorDestinatario,
            $nre,
            $this->transactionId,
            $this->response
        );
    }

    /**
     * Obtém metadados do trâmite.
     *
     * @return bool|ProcessoEntity
     *
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function receberTramite(int $idt, string $transactionId)
    {
        $this->idt = $idt;
        $this->transactionId = $transactionId;

        $tempoExecucao = microtime(true);

        $consultarTramitesResponse = $this->client->consultarTramites($this->idt);

        $pessoaRemetente = $this->repositorioToPessoa(
            $consultarTramitesResponse->tramitesEncontrados->tramite->remetente->identificacaoDoRepositorioDeEstruturas,
            $consultarTramitesResponse->tramitesEncontrados->tramite->remetente->numeroDeIdentificacaoDaEstrutura
        );

        $setorDestinatario = $this->repositorioToSetor(
            $consultarTramitesResponse->tramitesEncontrados->tramite
                ->destinatario->identificacaoDoRepositorioDeEstruturas,
            $consultarTramitesResponse->tramitesEncontrados->tramite->destinatario->numeroDeIdentificacaoDaEstrutura
        );

        if (!$setorDestinatario) {
            throw new Exception('Setor destino do trâmite não existe.');
        }

        $params = new stdClass();
        $params->IDT = $idt;

        $this->response = $this->client->solicitarMetadados($params);

        if (!$this->response) {
            throw new Exception('Erro de conexão com o barramento');
        }

        $processoEntity = false;

        if (isset($this->response->metadados->processo)) {
            $processoEntity = $this->receberProcesso(
                $pessoaRemetente,
                $setorDestinatario,
                $this->response->metadados->NRE
            );
        }

        if (isset($this->response->metadados->documento)) {
            $processoEntity = $this->receberDocumento(
                $pessoaRemetente,
                $setorDestinatario,
                $this->response->metadados->NRE
            );
        }

        $this->logger->info((string)(microtime(true) - $tempoExecucao), ['status']);

        return $processoEntity;
    }

    /**
     * Recebe trâmite de processo do barramento.
     *
     * @throws ReflectionException
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function receberProcesso(
        PessoaEntity $pessoaRemetente,
        SetorEntity $setorDestinatario,
        string $nre
    ): ProcessoEntity {
        return $this->sincronizacaoProcesso->sincronizaProcesso(
            $this->idt,
            $this->response->metadados->processo,
            $pessoaRemetente,
            $setorDestinatario,
            $nre,
            $this->transactionId,
            $this->response
        );
    }

    /**
     * Converte um repositório do barramento em objeto Pessoa.
     *
     * @throws ORMException
     * @throws Exception
     */
    private function repositorioToPessoa(
        int $identificacaoDoRepositorioDeEstruturas,
        string $numeroDeIdentificacaoDaEstrutura
    ): PessoaEntity {
        $consultarEstruturasResponse = $this->client->consultarEstruturas(
            $identificacaoDoRepositorioDeEstruturas,
            (int) $numeroDeIdentificacaoDaEstrutura
        );

        /** @var VinculacaoPessoaBarramento $vinculacaoPessoaEntity */
        $vinculacaoPessoaEntity = $this->vinculacaoPessoaBarramentoResource->findOneBy(
            ['estrutura' => (int) $numeroDeIdentificacaoDaEstrutura]
        );

        // nao existe essa estrutura vinculada a uma pessoa entao cria pessoa e vinculação
        if (!$vinculacaoPessoaEntity) {
            $hierarquia_nome = '';
            if (isset($consultarEstruturasResponse->estruturasEncontradas->estrutura->hierarquia) &&
                isset($consultarEstruturasResponse->estruturasEncontradas->estrutura->hierarquia->nivel)) {
                foreach ($consultarEstruturasResponse->estruturasEncontradas
                             ->estrutura->hierarquia->nivel as $keyNivel => $hierarquia) {
                    // pegando níveis da hierarquia
                    if (0 === $keyNivel) {
                        $hierarquia_nome .= ' - '.$hierarquia->nome.'/';
                        continue;
                    }
                    if (3 === $keyNivel) {
                        $hierarquia_nome .= $hierarquia->sigla;
                        break;
                    }
                    $hierarquia_nome .= $hierarquia->sigla.'/';
                }
            }

            $pessoaDto = new PessoaDTO();
            $pessoaDto->setNome(
                $this->upperUtf8($consultarEstruturasResponse->estruturasEncontradas->estrutura->nome.
                    $hierarquia_nome)
            );

            /** @var ModalidadeQualificacaoPessoaEntity $modalidadeQualificacaoPessoaEntity */
            $modalidadeQualificacaoPessoaEntity = $this->modalidadeQualificacaoPessoaResource->findOneBy(
                ['valor' => 'PESSOA JURÍDICA']
            );

            $pessoaDto->setModalidadeQualificacaoPessoa($modalidadeQualificacaoPessoaEntity);
            $pessoaEntity = $this->pessoaResource->create($pessoaDto, $this->transactionId);

            //cria a vinculacao dessa pessoa com o barramento
            $vinculacaoPessoaBarramentoDTO = new VinculacaoPessoaBarramento();
            $vinculacaoPessoaBarramentoDTO->setPessoa($pessoaEntity);
            $vinculacaoPessoaBarramentoDTO->setEstrutura(
                (int) $consultarEstruturasResponse->estruturasEncontradas->estrutura->numeroDeIdentificacaoDaEstrutura
            );
            $vinculacaoPessoaBarramentoDTO->setNomeEstrutura(
                $consultarEstruturasResponse->estruturasEncontradas->estrutura->nome
            );
            $vinculacaoPessoaBarramentoDTO->setRepositorio((int) $identificacaoDoRepositorioDeEstruturas);

            $this->vinculacaoPessoaBarramentoResource->create($vinculacaoPessoaBarramentoDTO, $this->transactionId);
        } else {
            // ja existe entao retorna pessoa da vinculacao
            $pessoaEntity = $vinculacaoPessoaEntity->getPessoa();
        }

        return $pessoaEntity;
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
}
