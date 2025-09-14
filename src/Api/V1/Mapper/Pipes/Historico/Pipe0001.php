<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Historico/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Historico;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    protected RequestStack $requestStack;

    protected TokenStorageInterface $tokenStorage;

    protected VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoPessoaUsuarioResource = $vinculacaoPessoaUsuarioResource;
    }

    public function supports(): array
    {
        return [
            HistoricoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     *
     * @return void
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($entity->getProcesso() &&
            ((false === $this->authorizationChecker->isGranted('VIEW', $entity->getProcesso())) ||
            ($entity->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted('VIEW', $entity->getProcesso()->getClassificacao()))))
        ) {
            $restDto = new HistoricoDTO();
            $restDto->setId($entity->getId());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
