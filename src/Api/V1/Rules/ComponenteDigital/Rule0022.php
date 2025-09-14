<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0022.
 *
 * @descSwagger=Não é possível alterar o modelo de componente_digital assinado
 * @classeSwagger=Rule0022
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0022 implements RuleInterface
{
    /**
     * Rule0022 constructor.
     *
     * @param RulesTranslate    $rulesTranslate
     */
    public function __construct(
        private RulesTranslate $rulesTranslate
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getModelo() !== $entity->getModelo() && $entity->getAssinaturas()->count() > 0) {
            $this->rulesTranslate->throwException('componenteDigital', '0022');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
