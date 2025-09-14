<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatMensagem/Rule0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Somente participantes do chat podem enviar mensagens.
 * @classeSwagger=Rule0002
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(private RulesTranslate $rulesTranslate)
    {
    }

    public function supports(): array
    {
        return [
            ChatMensagem::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $participante = false;

        foreach ($restDto->getChat()->getParticipantes() as $chatParticipante) {
            if ($chatParticipante->getUsuario()->getId() === $restDto->getUsuario()->getId()) {
                $participante = true;
                break;
            }
        }

        if (!$participante) {
            $this->rulesTranslate->throwException('chatMensagem', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
