<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Chat/Rule0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Chat;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=Somente chats em grupo podem ser excluídos.
 * @classeSwagger=Rule0003
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(
        private RulesTranslate $rulesTranslate
    ) {
    }

    public function supports(): array
    {
        return [
            Chat::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$entity->getGrupo()) {
            $this->rulesTranslate->throwException('chat', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
