<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Etiqueta/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Etiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se o usuário possui permissão para excluír etiqueta
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private AuthorizationCheckerInterface $authorizationChecker;

    private VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->vinculacaoEtiquetaRepository = $vinculacaoEtiquetaRepository;
    }

    public function supports(): array
    {
        return [
            Etiqueta::class => [
                'beforeDelete',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Etiqueta|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Etiqueta|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))) {
            //VERIFICA SE A ETIQUETA PERTENCE AO USUÁRIO
            $vinculacaoUsuarioEtiqueta = $this->vinculacaoEtiquetaRepository
                ->findOneBy(['etiqueta' => $entity->getId()]);

            if ($this->tokenStorage->getToken()->getUser()->getId()
                !== $vinculacaoUsuarioEtiqueta->getUsuario()->getId()) {
                $this->rulesTranslate->throwException('etiqueta', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
