<?php

declare(strict_types=1);
/**
 * /src/Repository/ConfigModuloRepository.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Repository\BaseRepository;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as Entity;

/**
 * Class ConfigModuloRepository.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br> *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(int $id, string $lockMode = null, string $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
 * @method Entity[]    findByAdvanced(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $search = null) : array
 * @method Entity[]    findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class ConfigModuloRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static string $entityName = Entity::class;
}
