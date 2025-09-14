<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /tests/Integration/Entity//<?= $entityName ?>Test.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Tests\Integration\Entity;

use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?>;

/**
 * Class <?= $entityName ?>Test
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Entity
 * @author  <?= $author . "\n" ?>
 */
class <?= $entityName ?>Test extends EntityTestCase
{
    /**
     * @var string
     */
    protected $entityName = <?= $entityName ?>::class;
}
