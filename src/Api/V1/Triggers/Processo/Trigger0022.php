<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use function md5;
use function rand;
use function substr;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use function uniqid;

/**
 * Class Trigger0022.
 *
 * @descSwagger=Os processos novos tem uma chave de acesso gerada automaticamente!
 * @classeSwagger=Trigger0022
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0022 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?Processo $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getAlterarChave()) {
            $restDto->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));
        }
    }

    public function getOrder(): int
    {
        return 22;
    }
}
