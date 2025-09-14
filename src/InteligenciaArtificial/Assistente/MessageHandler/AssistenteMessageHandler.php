<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\MessageHandler;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\AssistenteService;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Message\AssistenteMessage;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Models\AssistentePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class GtpMessageHandler.
 */
#[AsMessageHandler]
class AssistenteMessageHandler
{
    /**
     * Constructor.
     *
     * @param AssistenteService   $assistenteService
     * @param DocumentoRepository $documentoRepository
     * @param HubInterface        $hub
     * @param TransactionManager  $transactionManager
     * @param LoggerInterface     $logger
     */
    public function __construct(
        private readonly AssistenteService $assistenteService,
        private readonly DocumentoRepository $documentoRepository,
        private readonly HubInterface $hub,
        private readonly TransactionManager $transactionManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param AssistenteMessage $message
     */
    public function __invoke(AssistenteMessage $message): void
    {
        try {
            $transactionId = $this->transactionManager->begin();
            $prompt = new AssistentePrompt(
                $message->getUserPrompt(),
                $message->getActionPrompt(),
                $message->getDocumentoId() ?
                    $this->documentoRepository->find($message->getDocumentoId()) : null,
                $message->getRag(),
                $message->getContext(),
                $message->getPersona()
            );
            $this->assistenteService->streamedCall(
                $prompt,
                $message->getUuid(),
                $message->getChannel()
            );
            $this->transactionManager->commit($transactionId);
        } catch (UnauthorizedDocumentAccessException|
        EmptyDocumentContentException|
        MaximumInputTokensExceededException $e) {
            $this->logger->critical(
                'Erro ao processar mensagem do assistente de inteligência artificial.',
                [
                    'uuid' => $message->getUuid(),
                    'documento_id' => $message->getDocumentoId(),
                    'channel' => $message->getChannel(),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]
            );
            $this->hub->publish(
                new Update(
                    ['assistente_ia/'.$message->getChannel()],
                    json_encode(
                        [
                            'assistente_ia' => [
                                'uuid' => $message->getUuid(),
                                'message' => $e->getMessage(),
                                'final' => true,
                            ],
                        ]
                    )
                )
            );
        } catch (Throwable $e) {
            $this->logger->critical(
                'Erro ao processar mensagem do assistente de inteligência artificial.',
                [
                    'uuid' => $message->getUuid(),
                    'channel' => $message->getChannel(),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
            $this->hub->publish(
                new Update(
                    ['assistente_ia/'.$message->getChannel()],
                    json_encode(
                        [
                            'assistente_ia' => [
                                'uuid' => $message->getUuid(),
                                'message' => 'Houve um erro no processamento da IA, tente novamente mais tarde...',
                                'final' => true,
                            ],
                        ]
                    )
                )
            );
        }
    }
}
