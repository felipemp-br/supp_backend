<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use Chrisguitarguy\RequestId\RequestIdStorage;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid as Ruuid;
use SuppCore\AdministrativoBackend\Messenger\Stamp\TraceStamp;
use Symfony\Component\Messenger\Envelope;

/**
 * TraceService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TraceService
{
    private const string HEADER_TRACE_KEY = 'request_id';
    private const string ENV_TRACE_KEY = 'REQUEST_ID';

    /**
     * Constructor.
     *
     * @param RequestIdStorage $requestIdStorage
     * @param LoggerInterface  $logger
     */
    public function __construct(
        private readonly RequestIdStorage $requestIdStorage,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return string
     */
    public function getEnvTraceKey(): string
    {
        return self::ENV_TRACE_KEY;
    }

    /**
     * @param Envelope $envelope
     *
     * @return Envelope
     */
    public function addTraceStamp(Envelope $envelope): Envelope
    {
        return $envelope->with(new TraceStamp(
            $this->getTraceId()
        ));
    }

    /**
     * @param Envelope $envelope
     *
     * @return void
     */
    public function loadTraceIdFromEnvelope(Envelope $envelope): void
    {
        $stamp = $envelope->last(TraceStamp::class);
        if ($stamp) {
            $this->setTraceId($stamp->getId());
        } elseif (!$this->requestIdStorage->getRequestId()) {
            $this->restartTraceId();
        }
    }

    /**
     * @param bool $createIfNotExist
     *
     * @return string|null
     */
    public function getTraceId(bool $createIfNotExist = true): ?string
    {
        if (!$this->requestIdStorage->getRequestId() && $createIfNotExist) {
            $this->restartTraceId();
        }
        return $this->requestIdStorage->getRequestId();
    }

    /**
     * @param bool $logTransition
     *
     * @return void
     */
    public function restartTraceId(bool $logTransition = false): void
    {
        $oldTraceId = $this->getTraceId(false);
        $newTraceId = Ruuid::uuid4()->toString();
        if ($logTransition && $oldTraceId) {
            $this->logger->info(
                sprintf(
                    'Alterando request id de %s para %s',
                    $oldTraceId,
                    $newTraceId
                )
            );
        }
        $this->setTraceId($newTraceId);
        if ($logTransition) {
            $this->logger->info(
                sprintf(
                    'Definindo novo request id %s.%s',
                    $newTraceId,
                    $oldTraceId ?
                        sprintf(
                            'Request id anterior: %s',
                            $oldTraceId
                        ) : ''
                )
            );
        }
    }

    /**
     * @param string $traceId
     *
     * @return void
     */
    protected function setTraceId(string $traceId): void
    {
        $this->requestIdStorage->setRequestId($traceId);
        putenv(sprintf(
            '%s=%s',
            $this->getEnvTraceKey(),
            $traceId
        ));
    }

    /**
     * @param string|null $envKey
     *
     * @return void
     */
    public function loadTraceIdFromEnvironment(?string $envKey = null): void
    {
        $envKey ??= $this->getEnvTraceKey();
        $traceId = getenv($envKey);
        if ($traceId) {
            $this->setTraceId($traceId);
        } elseif (!$this->requestIdStorage->getRequestId()) {
            $this->getTraceId();
        }
    }
}
