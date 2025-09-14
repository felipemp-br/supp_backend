<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Notificacao/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Notificacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Notificacao as NotificacaoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
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

    protected VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    /**
     * Pipe0001 constructor.
     *
     * @param TokenStorageInterface             $tokenStorage;
     * @param VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository,
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoPessoaUsuarioRepository = $vinculacaoPessoaUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            NotificacaoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param NotificacaoDTO|RestDtoInterface|null $restDto
     * @param NotificacaoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser()) {
            return;
        }
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))) {
            //VERIFICA SE A NOTIFICAÇÃO PERTENCE AO USUÁRIO LOGADO
            if ($this->tokenStorage->getToken()->getUser()->getId()
                            !== $entity->getDestinatario()->getId()) {
                $restDto = new NotificacaoDTO();
                $restDto->setId($entity->getId());
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
