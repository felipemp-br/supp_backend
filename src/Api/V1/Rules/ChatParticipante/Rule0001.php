<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatParticipante/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Somente o administrador pode remover participantes do chat ou o próprio partipante pode sair.
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
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
            ChatParticipante::class => [
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
            $this->rulesTranslate->throwException('chatParticipante', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
