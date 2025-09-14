<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatParticipante/Rule0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Usuários que já participam do chat não podem ser reinseridos.
 * @classeSwagger=Rule0002
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
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
            ChatParticipante::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        foreach ($restDto->getChat()->getParticipantes() as $chatParticipante) {
            if ($restDto->getUsuario()->getId() === $chatParticipante->getUsuario()->getId()) {
                $this->rulesTranslate->throwException('chatParticipante', '0002');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
