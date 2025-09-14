<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DocumentoAvulso/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoAvulso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0005.
 *
 * @descSwagger=Permissão de editar observação de oficio
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    /**
     * Rule0005 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository
    ) { }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeObservacao',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))) {
            $usuarioVinculado = $this->vinculacaoPessoaUsuarioRepository->findOneBy([
                'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                'pessoa' => $entity->getPessoaDestino()
            ]);
            
            if(!$usuarioVinculado) {
                $this->rulesTranslate->throwException('documentoAvulso', '0006');
            }            
        }
        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}
