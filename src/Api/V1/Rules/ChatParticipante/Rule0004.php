<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatParticipante/Rule0004.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=Somente um administrador do grupo pode adicionar participantes.
 *
 * @classeSwagger=Rule0004
 */
class Rule0004 implements RuleInterface
{
    /**
     * Rule0004 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage
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
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $administrador = false;

        if ($restDto->getChat()->getId() && $restDto->getChat()->getGrupo() && $this->tokenStorage->getToken()) {
            foreach ($restDto->getChat()->getParticipantes() as $participante) {
                if ($participante->getUsuario()->getId() === $this->tokenStorage->getToken()?->getUser()->getId()
                    && $participante->getAdministrador()) {
                    $administrador = true;
                    break;
                }
            }

            if (!$administrador) {
                $this->rulesTranslate->throwException('chatParticipante', '0004');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
