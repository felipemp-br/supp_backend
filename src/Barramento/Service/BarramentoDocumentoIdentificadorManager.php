<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIdentificador as DocumentoIdentificadorDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIdentificadorResource as DocumentoIdentificadorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeDocumentoIdentificadorResource as ModalidadeDocumentoIdentificadorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource as OrigemDadosResource;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as DocumentoIdentificador;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Repository\DocumentoIdentificadorRepository as DocumentoIdentificadorRepository;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos relacionados ao Documento Identificador.
 */
class BarramentoDocumentoIdentificadorManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Mapeamento da modalidade do documento identificador.
     */
    private array $mapModalidadeDocIdentificador;

    /**
     * Array de objetos a serem persistidos.
     */
    private array $toPersistEntities = [];

    /**
     * Array de objetos a serem removidos.
     */
    private array $toRemoveEntities = [];

    private DocumentoIdentificador $documentoIdentificador;

    private string $transactionId;

    /**
     * BarramentoDocumentoIdentificadorManager constructor.
     *
     * @param BarramentoLogger $logger
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        private DocumentoIdentificadorResource $documentoIdentificadorResource,
        private DocumentoIdentificadorRepository $documentoIdentificadorRepository,
        private ModalidadeDocumentoIdentificadorResource $modalidadeDocumentoIdentificadorResource,
        private OrigemDadosResource $origemDadosResource
    ) {
        $this->logger = $logger;
        $this->mapModalidadeDocIdentificador =
            $parameterBag->get('integracao_barramento')['mapeamentos']['modalidade_doc_identificador'];
    }

    /**
     * Sincroniza documento identificador da pessoa.
     *
     * @param $interessadoTramitacao
     * @param Pessoa $pessoa
     * @param $transactionId
     *
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function sincronizaDocumentoIdentificador($interessadoTramitacao, Pessoa $pessoa, $nre, $transactionId): mixed
    {
        $this->transactionId = $transactionId;
        if (isset($interessadoTramitacao->documentoDeIdentificacao)) {
            if (!is_array($interessadoTramitacao->documentoDeIdentificacao)) {
                $interessadoTramitacao->documentoDeIdentificacao = [
                    $interessadoTramitacao->documentoDeIdentificacao,
                ];
            }

            $documentosSupp = $this->documentoIdentificadorRepository
                ->findByPessoaAndFonteDados($pessoa->getId(), $this->fonteDeDados);

            $pessoa = $this->acrescentaDocumentos($interessadoTramitacao, $documentosSupp, $pessoa, $nre);

            $this->removeDocumentos($interessadoTramitacao, $documentosSupp);
        }

        return $pessoa;
    }

    /**
     * Compara documento identificador do barramento com o do Supp.
     */
    private function comparaDocumentoIdentificador(
        stdClass $docIdentBarramento,
        DocumentoIdentificador $docIdentSupp
    ): bool {
        $modalidade = $this->mapModalidadeDocIdentificador[$this->upperUtf8($docIdentBarramento->tipo)];

        if ($this->upperUtf8($docIdentBarramento->codigo) == $docIdentSupp->getCodigoDocumento() &&
            $this->upperUtf8($docIdentBarramento->emissor) == $docIdentSupp->getEmissorDocumento() &&
            $modalidade == $docIdentSupp->getModalidadeDocumentoIdentificador()->getValor()) {
            return true;
        }

        return false;
    }

    /**
     * @param $interessadoTramitacao
     * @param $documentosSupp
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function acrescentaDocumentos($interessadoTramitacao, $documentosSupp, Pessoa $pessoa, $nre): Pessoa
    {
        foreach ($interessadoTramitacao->documentoDeIdentificacao as $documentoDeIdentificacao) {
            $existe = false;

            if (!$this->getValorMapeado($this->mapModalidadeDocIdentificador, $documentoDeIdentificacao->tipo)) {
                $this->logger->critical('Modalidade de Documento Identificador não encontrada: '.
                    "$documentoDeIdentificacao->tipo");
                continue;
            }

            foreach ($documentosSupp as $documentoSupp) {
                if ($this->comparaDocumentoIdentificador($documentoDeIdentificacao, $documentoSupp)) {
                    $existe = true;
                }
            }

            if ((false == $existe) &&
                $this->getValueIfExist($documentoDeIdentificacao, 'codigo') &&
                $this->getValueIfExist($documentoDeIdentificacao, 'emissor')
            ) {
                $documentoNovoDTO = new DocumentoIdentificadorDTO();
                $documentoNovoDTO->setCodigoDocumento($documentoDeIdentificacao->codigo);
                $documentoNovoDTO->setNome(
                    $this->upperUtf8($this->getValueIfExist($documentoDeIdentificacao, 'nome'))
                );
                $documentoNovoDTO->setEmissorDocumento(
                    $this->upperUtf8($this->getValueIfExist($documentoDeIdentificacao, 'emissor'))
                );

                $modalidadeDocumentoIdentificadorEntity = $this->modalidadeDocumentoIdentificadorResource
                    ->findOneBy(['valor' => $this->mapModalidadeDocIdentificador[$documentoDeIdentificacao->tipo]]);

                $documentoNovoDTO->setModalidadeDocumentoIdentificador($modalidadeDocumentoIdentificadorEntity);

                $origemDadosDTO = $this->origemDadosFactory();
                $origemDadosDTO->setIdExterno($nre);
                $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTO, $this->transactionId);

                $documentoNovoDTO->setOrigemDados($origemDadosEntity);
                $documentoNovoDTO->getOrigemDados()->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);

                $documentoNovoDTO->setPessoa($pessoa);

                $this->documentoIdentificadorResource->create($documentoNovoDTO, $this->transactionId);
            }
        }

        return $pessoa;
    }

    /**
     * Acrescenta documentos de identificação para a pessoa.
     *
     * @param $interessadoTramitacao
     * @param array $documentosSupp - Objetos DocumentoIdentificador
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function removeDocumentos($interessadoTramitacao, array $documentosSupp): void
    {
        foreach ($documentosSupp as $documentoSupp) {
            $existe = false;

            foreach ($interessadoTramitacao->documentoDeIdentificacao as $documentoDeIdentificacao) {
                if ($this->comparaDocumentoIdentificador($documentoDeIdentificacao, $documentoSupp)) {
                    $existe = true;
                }
            }

            if (false == $existe) {
                $this->documentoIdentificadorResource->delete($documentoSupp, $this->transactionId);
            }
        }
    }
}
