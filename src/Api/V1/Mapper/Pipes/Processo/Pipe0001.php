<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0001.
 */
class Pipe0001 implements PipeInterface
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    private NUPProviderManager $nupProviderManager;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        NUPProviderManager $nupProviderManager
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->nupProviderManager = $nupProviderManager;
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param Processo|EntityInterface          $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ((false === $this->authorizationChecker->isGranted('VIEW', $entity)) ||
            ($entity->getClassificacao() &&
                $entity->getClassificacao()->getId() &&
                (false === $this->authorizationChecker->isGranted('VIEW', $entity->getClassificacao())))) {
            $restDto = new ProcessoDTO();
            $restDto->setId($entity->getId());
            $restDto->setNUP($entity->getNUP());
            $restDto->setNUPFormatado($this->nupProviderManager->getNupProvider($entity)
                ->formatarNumeroUnicoProtocolo($entity->getNUP()));
            $restDto->setAcessoNegado(true);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
