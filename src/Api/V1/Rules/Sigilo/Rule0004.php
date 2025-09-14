<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Sigilo/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Sigilo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=O Usuário não possui o Nível de Acesso necessário para atribuir esse sigilo!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Sigilo::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePacth',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Sigilo|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Sigilo|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->tokenStorage->getToken()->getUser()->getNivelAcesso() < $restDto->getTipoSigilo()->getNivelAcesso(
            )) {
            $this->rulesTranslate->throwException('sigilo', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
