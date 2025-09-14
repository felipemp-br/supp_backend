<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Chat/Rule0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Chat;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Somente o administrador do chat pode excluí-lo.
 * @classeSwagger=Rule0002
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private TokenStorageInterface $tokenStorage)
    {
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
        $podeExcluir = false;
        $usuarioLogadoId = $this->tokenStorage?->getToken()?->getUser()->getId();

        foreach ($restDto->getParticipantes() as $chatParticipante) {
            if ($chatParticipante->getAdministrador()
                && $chatParticipante->getUsuario()->getId() === $usuarioLogadoId) {
                $podeExcluir = true;
            }
            break;
        }

        if (!$podeExcluir) {
            $this->rulesTranslate->throwException('chat', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
