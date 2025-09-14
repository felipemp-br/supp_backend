<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DTO\Traits;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Trait FilesystemService.
 */
trait FilesystemService
{
    /**
     * FilesystemService.
     */
    #[Serializer\Exclude]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $filesystemService = null;

    public function setFilesystemService(string|null $filesystemService): self
    {
        $this->setVisited('filesystemService');

        $this->filesystemService = $filesystemService;

        return $this;
    }

    public function getFilesystemService(): string|null
    {
        return $this->filesystemService;
    }
}
