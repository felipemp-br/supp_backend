<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /tests/Integration/Integration/<?= $repositoryName ?>Test.php
 *
* @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Tests\Integration\Repository;

use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?>;
use SuppCore\AdministrativoBackend\Repository\<?= $repositoryName ?>;
use SuppCore\AdministrativoBackend\Resource\<?= $resourceName ?>;

/**
 * Class <?= $repositoryName ?>Test
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Repository
 * @author  <?= $author . "\n" ?>
 */
class <?= $repositoryName ?>Test extends RepositoryTestCase
{
    /**
     * @var string
     */
    protected $entityName = <?= $entityName ?>::class;

    /**
     * @var string
     */
    protected $repositoryName = <?= $repositoryName ?>::class;

    /**
     * @var string
     */
    protected $resourceName = <?= $resourceName ?>::class;

    /**
     * @var array
     */
    protected $associations = [
        'createdBy',
        'updatedBy',
    ];
}
