<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Doctrine\ORM\Pcu;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class PcuFilter.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PcuFilter extends SQLFilter
{
    /**
     * @param ClassMetadata $targetEntity
     * @param string        $targetTableAlias
     *
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $value = $this->getConnection()->getDatabasePlatform()->convertBooleans(false);

        if (isset($targetEntity->columnNames['pcu'])) {
            return $targetTableAlias.'.pcu = '.$value;
        }

        return '';
    }
}
