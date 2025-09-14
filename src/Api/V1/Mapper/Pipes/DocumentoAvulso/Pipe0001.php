<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/DocumentoAvulso/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\DocumentoAvulso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
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
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoPessoaUsuarioRepository = $vinculacaoPessoaUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulsoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DocumentoAvulsoDTO|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))) {
            // verifica se o documento está vinculado ao usuário logado conveniado
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioRepository
                    ->findOneBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                        'pessoa' => $entity->getPessoaDestino(), ]);

            if (!$vinculacaoUsuario) {
                $restDto = new DocumentoAvulsoDTO();
                $restDto->setId($entity->getId());
                unset($restDto);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
