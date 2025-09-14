<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Message/DownloadProcessoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message;

/**
 * Class DownloadProcessoMessage.
 */
class DownloadProcessoMessage
{
    public const DOWNLOAD_AS_PDF = 'PDF';
    public const DOWNLOAD_AS_ZIP = 'ZIP';

    public function __construct(
        private readonly string $uuid,
        private readonly string $typeDownload,
        private readonly string $username,
        private readonly string $sequencial,
        private readonly int $partNumber = 0,
        private readonly int $totalParts = 0
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTypeDownload(): string
    {
        return $this->typeDownload;
    }

    public function getSequencial(): string
    {
        return $this->sequencial;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPartNumber(): int
    {
        return $this->partNumber;
    }

    public function getTotalParts(): int
    {
        return $this->totalParts;
    }

}
