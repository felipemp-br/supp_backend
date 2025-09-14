<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /tests/Integration/Controller/<?= $controllerName ?>Test.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Tests\Integration\Controller;

use SuppCore\AdministrativoBackend\Controller\<?= $controllerName ?>;
use SuppCore\AdministrativoBackend\Resource\<?= $resourceName ?>;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class <?= $controllerName ?>Test
 *
 * @package Integration\Controller
 * @author  <?= $author . "\n" ?>
 */
class <?= $controllerName ?>Test extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected $controllerClass = <?= $controllerName ?>::class;

    /**
     * @var string
     */
    protected $resourceClass = <?= $resourceName ?>::class;
}
