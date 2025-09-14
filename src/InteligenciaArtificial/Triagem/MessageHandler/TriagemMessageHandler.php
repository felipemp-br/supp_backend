<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\MessageHandler;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIAMetadata;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIAMetadataResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakResource;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakStatus;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\FailureEvaluation;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Enums\StatusExecucaoTrilhaTriagem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Message\TriagemMessage;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * TriagemMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
#[CircuitBreakResource(
    TriagemMessageHandler::class,
    [
        self::SERVICE_KEY_TRIAGEM_IA,
    ]
)]
class TriagemMessageHandler
{
    private const string SERVICE_KEY_TRIAGEM_IA = 'triagem_ia';
    /**
     * Constructor.
     *
     * @param TrilhaTriagemManager        $trilhasTriagemManager
     * @param DocumentoResource           $documentoResource
     * @param DocumentoIAMetadataResource $documentoIAMetadataResource
     * @param TransactionManager          $transactionManager
     * @param LoggerInterface             $logger
     * @param CircuitBreakerService       $circuitBreakerService
     */
    public function __construct(
        private readonly TrilhaTriagemManager $trilhasTriagemManager,
        private readonly DocumentoResource $documentoResource,
        private readonly DocumentoIAMetadataResource $documentoIAMetadataResource,
        private readonly TransactionManager $transactionManager,
        private readonly LoggerInterface $logger,
        private readonly CircuitBreakerService $circuitBreakerService,
    ) {
    }

    /**
     * Handle do consumo da mensagem.
     *
     * @param TriagemMessage $message
     *
     * @return void
     *
     * @throws Exception
     */
    public function __invoke(TriagemMessage $message): void
    {
        $this->logger->info(
            'Documento recebido para triagem.',
            [
                'documento_uuid' => $message->getDocumentoUuid(),
                'force' => $message->getForce(),
                'context' => $message->getContext()
            ]
        );
        $documento = $this->documentoResource->findOneBy(
            ['uuid' => $message->getDocumentoUuid()]
        );
        if (!$documento) {
            throw new Exception(
                sprintf(
                    'Documento uuid: %s não encontrado.',
                    $message->getDocumentoUuid()
                ),
                404
            );
        }
        if (!$documento->getComponentesDigitais()->count()) {
            throw new Exception(
                sprintf(
                    'Documento uuid: %s não possuí componentes digitais.',
                    $message->getDocumentoUuid()
                ),
                400
            );
        }
        $this->circuitBreakerService->execute(
            self::SERVICE_KEY_TRIAGEM_IA,
            function () use ($message, $documento) {
                try {
                    $statusIniciada = StatusExecucaoTrilhaTriagem::INICIADA;
                    if ($documento->getDocumentoIAMetadata()
                        && $documento->getDocumentoIAMetadata()->getStatusExecucaoTrilhaTriagem() === $statusIniciada
                        && !$message->getForce()) {
                        $this->logger->error(sprintf(
                                'Já existe uma triagem em andamento para o documento uuid: %s.',
                                $documento->getUuid()
                            )
                        );
                        return;
                    }
                    $this->triagemIniciada($documento);
                    $result = $this->trilhasTriagemManager->executarTriagem(
                        new TrilhaTriagemInput(
                            $documento,
                            $message->context,
                            $message->force
                        )
                    );
                    $this->triagemFinalizada($documento, $result);
                } catch (Throwable $e) {
                    $this->logger->error(
                        'Erro na execução da triagem do documento.',
                        [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'documento_uuid' => $message->getDocumentoUuid(),
                            'code' => $e->getCode(),
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                        ]
                    );
                    $this->triagemFinalizada(
                        $documento,
                        criticalError: true
                    );
                    throw $e;
                }
            },
            fn (CircuitBreakStatus $status) => true,
            function (CircuitBreakStatus $status, Throwable $e) use ($documento) {
                if ($e instanceof ClientRateLimitExeededException) {
                    $this->triagemFinalizada($documento, criticalError: true);
                    return new FailureEvaluation(
                        true,
                        $e->getResetTimeout(),
                        1
                    );
                };
            }
        );
    }

    /**
     * Marca a triagem do documento como iniciada.
     *
     * @param Documento $documento
     *
     * @return void
     *
     * @throws Exception
     */
    private function triagemIniciada(Documento $documento): void
    {
        $this->logger->info(
            'Iniciando triagem do documento.',
            [
                'documento_uuid' => $documento->getUuid(),
            ]
        );
        $transactionId = $this->transactionManager->begin();
        $this->documentoIAMetadataResource->updateOrCreate(
            (new DocumentoIAMetadata())
                ->setDocumento($documento)
                ->setStatusExecucaoTrilhaTriagem(StatusExecucaoTrilhaTriagem::INICIADA)
                ->setDataExecucaoTrilhaTriagem(new DateTime()),
            $transactionId
        );
        $this->transactionManager->commit($transactionId);
    }

    /**
     * Marca a triagem do documento conforme retorno da triagem.
     *
     * @param Documento $documento
     * @param array     $result
     * @param bool      $criticalError
     *
     * @return void
     *
     * @throws Exception
     */
    private function triagemFinalizada(Documento $documento, array $result = [], bool $criticalError = false): void
    {
        $transactionId = $this->transactionManager->begin();
        $status = match (true) {
            $criticalError || !empty($result['erro']) && empty($result['sucesso']) => StatusExecucaoTrilhaTriagem::ERRO,
            empty($result['erro']) && !empty($result['sucesso']) => StatusExecucaoTrilhaTriagem::SUCESSO,
            default => StatusExecucaoTrilhaTriagem::SUPRIMIDA
        };
        $this->logger->info(
            sprintf(
                'Finalizando triagem do documento com status %s',
                $status->name
            ),
            [
                'documento_uuid' => $documento->getUuid(),
                'trilhas_sucesso' => $result['sucesso'] ?? [],
                'trilhas_erro' => $result['erro'] ?? [],
                'trilhas_sem_suporte' => $result['sem_suporte'] ?? [],
            ]
        );
        $dto = (new DocumentoIAMetadata())
            ->setDocumento($documento)
            ->setStatusExecucaoTrilhaTriagem($status);
        $this->documentoIAMetadataResource->updateOrCreate($dto, $transactionId);
        $this->transactionManager->commit($transactionId);
    }
}
