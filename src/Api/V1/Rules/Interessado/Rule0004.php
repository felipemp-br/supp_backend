<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Interessado/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Interessado;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=A modalidade de interessados criados pela integração não podem ser editados pelos usuários!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Interessado::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado|RestDtoInterface|null $restDto
     * @param Interessado|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getOrigemDados() && 'SICAU' !== $entity->getOrigemDados()->getFonteDados()) {
            $this->rulesTranslate->throwException('interessado', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
