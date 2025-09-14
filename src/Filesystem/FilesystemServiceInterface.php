<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Filesystem;

use Gaufrette\Filesystem;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class FilesystemService.
 */
interface FilesystemServiceInterface
{
    /**
     * @return Filesystem
     */
    public function get(): Filesystem;

    /**
     * @return int
     */
    public function getOrder(): int;

    /**
     * @return string
     */
    public function getClassname(): string;

    /**
     * @param EntityInterface|null $entity
     * @param RestDtoInterface|null $restDto
     * @return bool
     */
    public function supports(EntityInterface|null $entity = null, RestDtoInterface|null $restDto = null): bool;
}
