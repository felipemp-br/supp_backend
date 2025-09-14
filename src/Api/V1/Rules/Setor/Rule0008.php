<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Setor/Rule0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Setor;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0008.
 *
 * @descSwagger=Não é possível atribuir o setor como seu setor pai!
 * @classeSwagger=Rule0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0008 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Setor::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Setor|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$entity->getParent()) { // unidade
            return true;
        }

        if ($restDto->getParent()->getId() === $restDto->getId()) {
            $this->rulesTranslate->throwException('setor', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 8;
    }
}
