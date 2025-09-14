<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Helpers\DocumentoHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Message\TriagemMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * ConfigModuloTriagemHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfigModuloTriagemHelper
{
    private const string CONFIG_KEY = 'supp_core.administrativo_backend.ia.triagem';

    private readonly array $config;

    /**
     * Constructor.
     *
     * @param DocumentoHelper    $documentoHelper
     * @param TransactionManager $transactionManager
     * @param LoggerInterface    $logger
     * @param SuppParameterBag   $suppParameterBag
     */
    public function __construct(
        private readonly DocumentoHelper $documentoHelper,
        private readonly TransactionManager $transactionManager,
        private readonly LoggerInterface $logger,
        SuppParameterBag $suppParameterBag
    ) {
        $config = [];
        if ($suppParameterBag->has(self::CONFIG_KEY)) {
            $config = $suppParameterBag->get(self::CONFIG_KEY);
        }
        $this->config = $config;
    }

    public function hasConfig(): bool
    {
        return !empty($this->config);
    }

    public function isAtivo(): bool
    {
        return $this->config['ativo'] ?? false;
    }

    public function apenasClassificados(): bool
    {
        return $this->config['apenas_classificados'] ?? false;
    }

    public function apenasIntegracao(): bool
    {
        return $this->config['apenas_integracao'] ?? false;
    }

    public function executaTriagemJuntada(): bool
    {
        if ($this->hasConfig()) {
            return $this->config['executa_triagem_juntada'] ?? false;
        }
        return false;
    }

    public function isDocumentoIntegracao(DocumentoDTO|DocumentoEntity $documento): bool
    {
        return !!$documento->getOrigemDados();
    }

    public function apenasTipoDocumentoIgual(): bool
    {
        return $this->config['apenas_tipo_documento_igual'] ?? false;
    }

    public function isTipoDocumentoIgual(DocumentoEntity|DocumentoDTO $documento): bool
    {
        $tipoDocumento = $documento->getTipoDocumento()->getId();
        $tipoDocumentoPredito = $documento->getDocumentoIAMetadata()?->getTipoDocumentoPredito()?->getId();
        return $tipoDocumentoPredito === $tipoDocumento;
    }

    public function isClassificado(DocumentoEntity|DocumentoDTO $documento): bool
    {
        return !!$documento->getDocumentoIAMetadata()?->getTipoDocumentoPredito();
    }

    public function suportaEspecieProcesso(DocumentoDTO|DocumentoEntity $documento): bool
    {
        $especieProcesso = $documento
            ?->getJuntadaAtual()
            ?->getVolume()
            ?->getProcesso()
            ?->getEspecieProcesso();
        $especiesProcesso = array_map(
            fn (string $nomeEspecieProcesso) => mb_strtolower($nomeEspecieProcesso),
            $this->config['especies_processo']
        );
        if (empty($especiesProcesso)
            || $especieProcesso && in_array(mb_strtolower($especieProcesso->getNome()), $especiesProcesso)) {
            return true;
        }

        return false;
    }

    public function validate(
        DocumentoDTO|DocumentoEntity|null $documento,
        bool $throwException
    ): bool {
        try {
            if (!$documento) {
                throw new Exception(
                    'Documento não encontrado.'
                );
            }
            if (!$documento->getJuntadaAtual()) {
                throw new Exception(
                    sprintf(
                        'O documento uuid: %s, não possui juntada atual.',
                        $documento->getUuid()
                    )
                );
            }
            if (!$this->hasConfig()) {
                throw new Exception(
                    sprintf(
                        'Não existe configuração para o ConfigModulo %s',
                        self::CONFIG_KEY
                    )
                );
            }
            if (!$this->isAtivo()) {
                throw new Exception(
                    'A triagem de documentos está inativa.'
                );
            }
            if ($this->apenasIntegracao() && !$this->isDocumentoIntegracao($documento)) {
                throw new Exception(
                    'Apenas documentos vindos de integração podem ser triados.'
                );
            }
            if (!$this->suportaEspecieProcesso($documento)) {
                throw new Exception(
                    'Espécie de processo não suportado para triagens de documentos.'
                );
            }
            if ($this->apenasClassificados() && !$this->isClassificado($documento)) {
                throw new Exception(
                    'Apenas documentos classificados podem ser triados.'
                );
            }
            if ($this->apenasTipoDocumentoIgual() && !$this->isTipoDocumentoIgual($documento)) {
                throw new Exception(
                    'Apenas documentos com o tipo documento igual ao tipo documento predito podem ser triados.'
                );
            }
            if ($documento->getId() && !$this->documentoHelper->checkPermissions($documento)) {
                throw new Exception(
                    'Não é permitido o uso de documentos com restrição de acesso!'
                );
            }
            if (!$documento->getComponentesDigitais()->count()) {
                throw new Exception(
                    sprintf(
                        'Documento uuid: %s não possuí componentes digitais.',
                        $documento->getUuid()
                    )
                );
            }
            return true;
        } catch (Exception $e) {
            if ($throwException) {
                throw $e;
            }
            $this->logger->info(
                'Documento não enviado para fila de triagem por não atender os critérios da configuração.',
                [
                    'documento_uuid' => $documento->getUuid(),
                    'message' => $e->getMessage(),
                ]
            );
            return false;
        }
    }

    public function checkAndDispatchTriagemMessage(
        DocumentoEntity|DocumentoDTO|null $documento,
        string $transactionId,
        bool $force = false,
        bool $thowException = false
    ): void {
        if ($this->validate($documento, $thowException)) {
            $sameMessage = array_filter(
                $this->transactionManager->getAsyncDispatchs()[$transactionId] ?? [],
                fn ($message) => $message instanceof TriagemMessage
                    && $message->getDocumentoUuid() === $documento->getUuid()
            );
            if (empty($sameMessage)) {
                $this->logger->info(
                    'Preparando documento para envio para a fila de triagem.',
                    [
                        'documento_uuid' => $documento->getUuid(),
                    ]
                );
                $this->transactionManager->addAsyncDispatch(
                    new TriagemMessage(
                        $documento->getUuid(),
                        force: $force
                    ),
                    $transactionId
                );
            }
        }
    }
}
