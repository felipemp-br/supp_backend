<?php
declare(strict_types=1);
/**
 * /tests/bootstrap_fastest.php
 *
 * @package SuppCore\AdministrativoBackend\Tests
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

// Specify used environment file
putenv('ENVIRONMENT_FILE=.env.test');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';
