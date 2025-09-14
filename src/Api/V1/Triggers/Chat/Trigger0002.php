<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Chat/Trigger0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Limpa os atributos do chat se o mesmo for individual.
 * @classeSwagger=Trigger0002
 */
class Trigger0002 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ChatDTO|null $restDto
     * @param EntityInterface|ChatEntity    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto,
                            EntityInterface $entity,
                            string $transactionId): void
    {
        if (!$restDto->getGrupo()) {
            $restDto
                ->setDescricao(null)
                ->setNome(null)
                ->setCapa(null);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
