<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatMensagem/Rule0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Somente administradores ou autores das mensagens podem excluí-las.
 * @classeSwagger=Rule0003
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            ChatMensagem::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $podeExcluir = false;
        $usuarioLogadoId = $this->tokenStorage?->getToken()?->getUser()->getId();

        if ($usuarioLogadoId === $entity->getUsuario()->getId()) {
            $podeExcluir = true;
        } else {
            foreach ($entity->getChat()->getParticipantes() as $chatParticipante) {
                if ($chatParticipante->getUsuario()->getId() === $usuarioLogadoId
                    && $chatParticipante->getAdministrador()) {
                    $podeExcluir = true;
                    break;
                }
            }
        }

        if (!$podeExcluir) {
            $this->rulesTranslate->throwException('chatMensagem', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
