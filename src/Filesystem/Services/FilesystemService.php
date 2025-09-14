<?php

declare(strict_types=1);
/**
 * /src/Utils/FilesystemService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Filesystem\Services;

use Gaufrette\Filesystem;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemServiceInterface;
use SuppCore\AdministrativoBackend\Gaufrette\Adapter\SafeLocal;
use Symfony\Component\VarExporter\LazyObjectInterface;

/**
 * Class FilesystemService.
 */
class FilesystemService implements FilesystemServiceInterface
{
    private Filesystem|null $filesytem = null;

    /**
     * FilesystemService constructor.
     * @param SafeLocal $adapter
     */
    public function __construct(private SafeLocal $adapter)
    {
        $this->filesytem = new Filesystem($this->adapter);
    }

    /**
     * @return Filesystem
     */
    public function get(): Filesystem
    {
        return $this->filesytem;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 100;
    }

    /**
     * @param EntityInterface|null $entity
     * @param RestDtoInterface|null $restDto
     * @return bool
     */
    public function supports(EntityInterface|null $entity = null, RestDtoInterface|null $restDto = null): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getClassname(): string
    {
        return $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this);
    }

}
