<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ChatParticipante/Rule0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=Administradores únicos no grupo não podem ser excluídos.
 * @classeSwagger=Rule0003
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     */
    public function __construct(private RulesTranslate $rulesTranslate)
    {
    }

    public function supports(): array
    {
        return [
            ChatParticipante::class => [
                'beforeDelete',
            ]
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getChat()->getGrupo()
            && $entity->getAdministrador()
            && $entity->getChat()->getParticipantes()->count() > 1) {

            $qtdAdmins = 0;
            foreach ($entity->getChat()->getParticipantes() as $chatParticipante) {
                if ($chatParticipante->getAdministrador()) {
                    ++$qtdAdmins;
                }
            }

            if ($qtdAdmins === 1) {
                $this->rulesTranslate->throwException('chatParticipante', '0003');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
