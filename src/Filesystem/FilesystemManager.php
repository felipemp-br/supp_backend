<?php


namespace SuppCore\AdministrativoBackend\Filesystem;

use Exception;
use RuntimeException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class FilesystemManager
 * @package SuppCore\AdministrativoBackend\Filesystem
 */
class FilesystemManager
{
    /** @var FilesystemServiceInterface[]  */
    private array $filesystemServices;

    /**
     * @param FilesystemServiceInterface $filesystemService
     * @param string $className
     */
    public function addFilesystemService(FilesystemServiceInterface $filesystemService, string $className): void
    {
        $this->filesystemServices[$className] = $filesystemService;
        $this->reorderFilesystemServices();
    }

    protected function reorderFilesystemServices(): void
    {
        uasort($this->filesystemServices, function($filesystemServiceA, $filesystemServiceB) {
            return $filesystemServiceA->getOrder() <=> $filesystemServiceB->getOrder();
        });
    }

    /**
     * @return FilesystemServiceInterface[]
     */
    public function getFilesystemServices(): array
    {
        return $this->filesystemServices;
    }

    /**
     * @param string $filesystemServiceClassname
     * @return FilesystemServiceInterface
     */
    protected function getFilesystemServiceByClassname(string $filesystemServiceClassname): FilesystemServiceInterface
    {
        if (!isset($this->filesystemServices[$filesystemServiceClassname])) {
            throw new RuntimeException('FilesystemService not found for classname '.$filesystemServiceClassname);
        }

        return $this->filesystemServices[$filesystemServiceClassname];
    }

    /**
     * @param FilesystemServiceInterface $filesystemServiceInterface
     * @return string
     */
    public function getFilesystemServiceClassname(FilesystemServiceInterface $filesystemServiceInterface): string
    {
        foreach ($this->filesystemServices as $classname => $filesystemService) {
            if ($filesystemService === $filesystemServiceInterface) {
                return $classname;
            }
        }
    }

    /**
     * @param EntityInterface|null $entity
     * @param RestDtoInterface|null $restDto
     * @return FilesystemServiceInterface
     */
    public function getFilesystemService(
        EntityInterface|null $entity = null, RestDtoInterface|null $restDto = null
    ) :FilesystemServiceInterface
    {
        if ($entity && method_exists($entity, 'getFilesystemService') && $entity->getFilesystemService()) {
            $filesystemService =  $this->getFilesystemServiceByClassname($entity->getFilesystemService());

            if ($filesystemService->supports($entity)) {
                return $filesystemService;
            }
        }

        if ($restDto && method_exists($restDto, 'getFilesystemService') && $restDto->getFilesystemService()) {
            $filesystemService =  $this->getFilesystemServiceByClassname($restDto->getFilesystemService());

            if ($filesystemService->supports($entity, $restDto)) {
                return $filesystemService;
            }
        }

        foreach ($this->filesystemServices as $filesystemService) {
            if ($filesystemService->supports($entity, $restDto)) {
                return $filesystemService;
            }
        }

        throw new RuntimeException('No supported FilesystemService found.');
    }

}
