<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoPessoaUsuario/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoPessoaUsuario;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Altera se a pessoa como conveniada!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private PessoaResource $pessoaResource;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        PessoaResource $pessoaResource,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->pessoaResource = $pessoaResource;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            VinculacaoPessoaUsuarioDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoPessoaUsuarioDTO|RestDtoInterface|null $vinculacaoPessoaUsuarioDTO
     * @param VinculacaoPessoaUsuarioEntity|EntityInterface    $vinculacaoPessoaUsuarioEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $vinculacaoPessoaUsuarioDTO,
        EntityInterface $vinculacaoPessoaUsuarioEntity,
        string $transactionId
    ): void {
        if ($this->tokenStorage->getToken() &&
            $this->authorizationChecker->isGranted('ROLE_ADMIN') &&
            $vinculacaoPessoaUsuarioDTO->getPessoa()->getId()) {
            $pessoaDTO = new PessoaDTO();
            $pessoaDTO->setPessoaConveniada(true);
            $this->pessoaResource->update(
                $vinculacaoPessoaUsuarioDTO->getPessoa()->getId(),
                $pessoaDTO,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
