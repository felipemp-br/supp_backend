<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Throwable;

/**
 * TrilhaTriagemManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TrilhaTriagemManager
{
    /**
     * Constructor.
     *
     * @param LoggerInterface    $logger
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TransactionManager $transactionManager,
    ) {
    }

    /**
     * @var TrilhaTriagemInterface[]
     */
    private array $trilhasTriagem;

    /**
     * @param array $trilhasTriagem
     *
     * @return $this
     */
    public function setTrilhasTriagem(array $trilhasTriagem): self
    {
        $this->trilhasTriagem = $trilhasTriagem;

        return $this;
    }

    /**
     * @param TrilhaTriagemInput|null $input
     *
     * @return TrilhaTriagemInterface[]
     */
    public function getTrilhasTriagem(?TrilhaTriagemInput $input = null): array
    {
        if (!$input) {
            return $this->trilhasTriagem;
        }

        return array_filter(
            $this->trilhasTriagem,
            fn (TrilhaTriagemInterface $trilha) => $trilha->supports($input)
        );
    }

    /**
     * Executa a triagem do documento.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return array
     */
    public function executarTriagem(
        TrilhaTriagemInput $input
    ): array {
        $resultadoTriagem = [
            'sucesso' => [],
            'erro' => [],
            'sem_suporte' => [],
        ];
        foreach ($this->getTrilhasTriagem() as $trilha) {
            if ($trilha->supports($input)) {
                foreach ($trilha->getDeppendsOn() as $dependency) {
                    if (!in_array($dependency, $resultadoTriagem['sucesso'])) {
                        $resultadoTriagem['erro'][] = $dependency;
                        $this->logger->critical(
                            sprintf(
                                'Não foi possível executar a trilha'
                                . ' pois uma ou mais trilhas dependentes não foram executadas'
                                . ' por erro ou por não ser suportada.',
                                $trilha::class,
                                $dependency
                            ),
                            [
                                'documento_uuid' => $input->documento->getUuid(),
                                'trilha' => get_class($trilha),
                                'dependencia' => $dependency,
                            ]
                        );
                        continue 2;
                    }
                }
                try {
                    $transactionId = $this->transactionManager->begin();
                    $trilha->handle($input, $transactionId);
                    $resultadoTriagem['sucesso'][] = get_class($trilha);
                    $this->transactionManager->commit($transactionId);
                } catch (ClientRateLimitExeededException $e) {
                    $this->logger->critical(
                        'Erro na execução da trilha de triagem causado por rate limit.',
                        [
                            'documento_uuid' => $input->documento->getUuid(),
                            'trilha' => get_class($trilha),
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'code' => $e->getCode()
                        ]
                    );
                    throw $e;
                } catch (Throwable $e) {
                    $this->logger->critical(
                        'Erro na execução da trilha de triagem.',
                        [
                            'documento_uuid' => $input->documento->getUuid(),
                            'trilha' => get_class($trilha),
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'code' => $e->getCode()
                        ]
                    );
                    $resultadoTriagem['erro'][] = get_class($trilha);
                    $this->transactionManager->resetTransaction($transactionId ?? null);
                }
            } else {
                $resultadoTriagem['sem_suporte'][] = get_class($trilha);
            }
        }

        return $resultadoTriagem;
    }
}
