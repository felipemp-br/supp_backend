<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/RestResourceFindOne.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Trait RestResourceFindOne.
 *
 * @SuppressWarnings("unused")
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait RestResourceFindOne
{
    /**
     * Before lifecycle method for findOne method.
     */
    public function beforeFindOne(
        string $className,
        int &$id,
        ?array &$populate = null,
        ?array &$orderBy = null,
        ?array &$context = null,
        ?EntityInterface &$entity = null
    ): void {
        $this->triggersManager->proccessReadOne(
            $className,
            $id,
            $populate,
            $orderBy,
            $context,
            $entity,
            'beforeFindOne'
        );
    }

    /**
     * After lifecycle method for findOne method.
     *
     * Notes:   If you make changes to entity in this lifecycle method by default it will be saved on end of current
     *          request. To prevent this you need to detach current entity from entity manager.
     *
     *          Also note that if you've made some changes to entity and you eg. throw an exception within this method
     *          your entity will be saved if it has eg Blameable / Timestampable traits attached.
     */
    public function afterFindOne(
        string $className,
        int &$id,
        ?array &$populate = null,
        ?array &$orderBy = null,
        ?array &$context = null,
        ?EntityInterface &$entity = null
    ): void {
        $this->triggersManager->proccessReadOne(
            $className,
            $id,
            $populate,
            $orderBy,
            $context,
            $entity,
            'afterFindOne'
        );
    }
}
