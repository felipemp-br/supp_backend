<?php


namespace SuppCore\AdministrativoBackend\Crypto;

use Exception;
use RuntimeException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class CryptoManager
 * @package SuppCore\AdministrativoBackend\Crypto
 */
class CryptoManager
{
    /** @var CryptoServiceInterface[]  */
    private array $cryptoServices;

    /**
     * @param CryptoServiceInterface $cryptoService
     * @param string $className
     */
    public function addCryptoService(CryptoServiceInterface $cryptoService, string $className): void
    {
        $this->cryptoServices[$className] = $cryptoService;
        $this->reorderCryptoServices();
    }

    protected function reorderCryptoServices(): void
    {
        uasort($this->cryptoServices, function($cryptoServiceA, $cryptoServiceB) {
            return $cryptoServiceA->getOrder() <=> $cryptoServiceB->getOrder();
        });
    }

    public function getCryptoServices(): array
    {
        return $this->cryptoServices;
    }

    /**
     * @param $cryptoServiceClassname
     * @return CryptoServiceInterface
     */
    protected function getCryptoServiceByClassname($cryptoServiceClassname): CryptoServiceInterface
    {
        if (!isset($this->cryptoServices[$cryptoServiceClassname])) {
            throw new RuntimeException('CryptoService not found for classname '.$cryptoServiceClassname);
        }

        return $this->cryptoServices[$cryptoServiceClassname];
    }

    /**
     * @param EntityInterface|null $entity
     * @param RestDtoInterface|null $restDto
     * @return CryptoServiceInterface
     */
    public function getCryptoService(
        EntityInterface|null $entity = null, RestDtoInterface|null $restDto = null
    ) :CryptoServiceInterface
    {
        if ($entity && method_exists($entity, 'getCryptoService') && $entity->getCryptoService()) {
            $cryptoService =  $this->getCryptoServiceByClassname($entity->getCryptoService());

            if ($cryptoService->supports($entity)) {
                return $cryptoService;
            }
        }

        if ($restDto && method_exists($restDto, 'getCryptoService') && $restDto->getCryptoService()) {
            $cryptoService =  $this->getCryptoServiceByClassname($restDto->getCryptoService());

            if ($cryptoService->supports($entity, $restDto)) {
                return $cryptoService;
            }
        }

        foreach ($this->cryptoServices as $cryptoService) {
            if ($cryptoService->supports($entity)) {
                return $cryptoService;
            }
        }
    }

}
