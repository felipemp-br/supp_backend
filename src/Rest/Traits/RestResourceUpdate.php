<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/RestResourceUpdate.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Trait RestResourceUpdate.
 *
 * @SuppressWarnings("unused")
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait RestResourceUpdate
{
    /**
     * Before lifecycle method for update method.
     */
    public function beforeUpdate(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertUpdate');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeUpdate');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeUpdate');
    }

    /**
     * After lifecycle method for update method.
     *
     * Notes:   If you make changes to entity in this lifecycle method by default it will be saved on end of current
     *          request. To prevent this you need to detach current entity from entity manager.
     *
     *          Also note that if you've made some changes to entity and you eg. throw an exception within this method
     *          your entity will be saved if it has eg Blameable / Timestampable traits attached.
     */
    public function afterUpdate(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterUpdate');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'afterUpdate');
    }
}
