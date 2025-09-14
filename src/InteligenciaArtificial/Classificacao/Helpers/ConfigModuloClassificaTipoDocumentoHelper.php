<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao\Helpers;

use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao\Message\ClassificaDocumentoMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * ConfigModuloClassificaTipoDocumentoHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfigModuloClassificaTipoDocumentoHelper
{
    private const string CONFIG_KEY = 'supp_core.administrativo_backend.ia.classifica_tipo_documento';

    private readonly array $config;

    /**
     * Constructor.
     *
     * @param TransactionManager $transactionManager
     * @param LoggerInterface    $logger
     * @param SuppParameterBag   $suppParameterBag
     */
    public function __construct(
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

    public function apenasIntegracao(): bool
    {
        return $this->config['apenas_integracao'] ?? false;
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

    public function isDocumentoIntegracao(DocumentoDTO|DocumentoEntity $documento): bool
    {
        return !!$documento->getOrigemDados();
    }

    public function validate(
        DocumentoDTO|DocumentoEntity|null $documento,
        string $transactionId,
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
                    'Apenas documentos juntados podem ser classificados.'
                );
            }
            if ($this->transactionManager->getContext('clonarDocumento', $transactionId)) {
                throw new Exception(
                    sprintf(
                        'O documento %s, esta sendo clonado.',
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
                    'A classificação de documentos está inativa.'
                );
            }
            if ($this->apenasIntegracao() && !$this->isDocumentoIntegracao($documento)) {
                throw new Exception(
                    'Apenas documentos originados de integração podem ser classificados.'
                );
            }
            if (!$this->suportaEspecieProcesso($documento)) {
                throw new Exception(
                    sprintf(
                        'Espécie de processo não suportada para o documento uuid: %s.',
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
                'Documento não enviado para fila de classificação por não atender os critérios da configuração.',
                [
                    'documento_uuid' => $documento->getUuid(),
                    'message' => $e->getMessage(),
                ]
            );
            return false;
        }
    }

    public function checkAndDispatchClassificaDocumentoMessage(
        DocumentoEntity|DocumentoDTO|ComponenteDigitalEntity|ComponenteDigitalDTO|null $documento,
        string $transactionId,
        bool $throwException = false
    ): void {
        if ($documento instanceof ComponenteDigitalDTO || $documento instanceof ComponenteDigitalEntity) {
            $documento = $documento->getDocumento();
        }
        if ($this->validate($documento, $transactionId, $throwException)) {
            $sameMessage = array_filter(
                $this->transactionManager->getAsyncDispatchs()[$transactionId] ?? [],
                fn ($message) => $message instanceof ClassificaDocumentoMessage
                    && $message->getDocumentoUuid() === $documento->getUuid()
            );
            if (empty($sameMessage)) {
                $this->logger->info(
                    'Preparando documento para envio para a fila de classificação.',
                    [
                        'documento_uuid' => $documento->getUuid(),
                    ]
                );
                $this->transactionManager->addAsyncDispatch(
                    new ClassificaDocumentoMessage(
                        $documento->getUuid()
                    ),
                    $transactionId
                );
            }
        }
    }
}
