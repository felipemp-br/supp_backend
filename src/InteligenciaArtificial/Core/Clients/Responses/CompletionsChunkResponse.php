<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Responses;


use Symfony\Contracts\HttpClient\ChunkInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * CompletionsChunkResponse.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompletionsChunkResponse implements ChunkInterface
{
    /**
     * Constructor.
     *
     * @param string      $content
     * @param bool        $timeout
     * @param bool        $first
     * @param bool        $last
     * @param int         $offset
     * @param string|null $error
     */
    public function __construct(
        private readonly string $content,
        private readonly bool $timeout,
        private readonly bool $first,
        private readonly bool $last,
        private readonly int $offset,
        private readonly ?string $error = null,
    ) {
    }

    /**
     * Tells when the idle timeout has been reached.
     *
     * @throws TransportExceptionInterface
     */
    public function isTimeout(): bool
    {
        return $this->timeout;
    }

    /**
     * Tells when headers just arrived.
     *
     * @throws TransportExceptionInterface
     */
    public function isFirst(): bool
    {
        return $this->first;
    }

    /**
     * Tells when the body just completed.
     *
     * @throws TransportExceptionInterface
     */
    public function isLast(): bool
    {
        return $this->last;
    }

    /**
     * Returns a [status code, headers] tuple when a 1xx status code was just received.
     *
     * @throws TransportExceptionInterface
     */
    public function getInformationalStatus(): ?array
    {
        return null;
    }

    /**
     * Returns the content of the response chunk.
     *
     * @throws TransportExceptionInterface
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Returns the offset of the chunk in the response body.
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * In case of error, returns the message that describes it.
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
