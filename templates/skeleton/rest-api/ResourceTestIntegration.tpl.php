<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /tests/Integration/Resource/<?= $resourceName ?>Test.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Tests\Integration\Resource;

use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?>;
use SuppCore\AdministrativoBackend\Repository\<?= $repositoryName ?>;
use SuppCore\AdministrativoBackend\Resource\<?= $resourceName ?>;

/**
 * Class <?= $resourceName ?>Test
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Resource
 * @author  <?= $author . "\n" ?>
 */
class <?= $resourceName ?>Test extends ResourceTestCase
{
    protected $entityClass = <?= $entityName ?>::class;
    protected $resourceClass = <?= $resourceName ?>::class;
    protected $repositoryClass = <?= $repositoryName ?>::class;
}
