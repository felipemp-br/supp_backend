<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Desentranhamento/Rule0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007.
 *
 * @descSwagger=Este documento já foi desentranhado!
 * @classeSwagger=Rule0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0007 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Desentranhamento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getJuntada()->getAtivo()) {
            $this->rulesTranslate->throwException('desentranhamento', '0007');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}
