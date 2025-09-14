<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0008.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0008.
 */
class Pipe0008 implements PipeInterface
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Pipe0008 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            DocumentoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DocumentoDTO|RestDtoInterface|null $restDto
     * @param Documento|EntityInterface          $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ((false === $this->authorizationChecker->isGranted('VIEW', $entity)) ||
            (
                $entity->getJuntadaAtual() &&
                (false === $this->authorizationChecker->isGranted('VIEW', $entity->getJuntadaAtual()->getVolume()->getProcesso()))
            ) ||
            (
                $entity->getJuntadaAtual()?->getVolume()->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted('VIEW', $entity->getJuntadaAtual()->getVolume()->getProcesso()->getClassificacao())))
        ) {
            $restDto->setAcessoNegado(true);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
