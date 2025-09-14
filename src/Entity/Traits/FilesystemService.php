<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait FilesystemService.
 */
trait FilesystemService
{
    #[Gedmo\Versioned]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[ORM\Column(type: 'string', nullable: true)]
    protected string|null $filesystemService = '';

    public function setFilesystemService(string|null $filesystemService): self
    {
        $this->filesystemService = $filesystemService;

        return $this;
    }

    public function getFilesystemService(): string|null
    {
        return $this->filesystemService;
    }
}
