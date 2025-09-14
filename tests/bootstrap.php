<?php

declare(strict_types=1);
/**
 * /tests/bootstrap.php.
 *
 * Bootstrap for PHPUnit tests, basically we need to do following things:
 *  1) Load test environment variables
 *  2) Boot kernel and create console application with that
 *  3) Drop test environment database
 *  4) Create empty database to test environment
 *  5) Update database schema
 *  6) Create user roles to database
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
use SuppCore\AdministrativoBackend\Kernel;
use SuppCore\AdministrativoBackend\Utils\Tests\FixturesLoader;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require __DIR__.'/../vendor/autoload.php';
// require __DIR__.'/../bootstrap.php';

(new Dotenv())->usePutenv(true)->loadEnv(dirname(__DIR__).'/.env.test');

$databaseFilePath = __DIR__.'/../var/cache/test/test.db';

$databaseCacheFile = sprintf(
    '%s%stest_database_cache%s.json',
    sys_get_temp_dir(),
    DIRECTORY_SEPARATOR,
    (string) getenv('ENV_TEST_CHANNEL_READABLE')
);

// Oh yeah, database is already created we don't want to do any lifting anymore \o/
if (is_readable($databaseCacheFile) && '' !== (string) getenv('ENV_TEST_CHANNEL_READABLE')) {
    return;
}

// Create and boot 'test' kernel
$kernel = new Kernel(getenv('APP_ENV'), (bool) getenv('APP_DEBUG'));
$kernel->boot();

// Create new application
global $application;
$application = new Application($kernel);
$application->setAutoExit(false);

$flushRedisCommand = static function () use ($application) {
    echo 'Flushing Redis cache... '.PHP_EOL;
    $input = new ArrayInput([
        'command' => 'redis:query',
        'query' => ['flushall'],
        '--no-interaction' => true,
    ]);

    $input->setInteractive(false);
    $application->run($input, new ConsoleOutput());
};

$createSchemaCommand = static function () use ($application) {
    echo 'Criando o Banco de Dados caso não exista... '.PHP_EOL;
    $input = new ArrayInput([
        'command' => 'doctrine:schema:create',
        '--env' => 'test',
        '--no-interaction' => true,
    ]);

    $input->setInteractive(false);

    $application->run($input, new ConsoleOutput());
    echo 'Criando o Banco de Dados caso não exista... OK!'.PHP_EOL;
};

// Add the doctrine:fixtures:load command to the application and run it
$loadFixturesDoctrineCommand = static function () use ($application) {
    echo 'Aplicando as Fixtures...'.PHP_EOL;

    /** @var FixturesLoader $fixtureLoader */
    $fixtureLoader = $application->getKernel()->getContainer()->get(FixturesLoader::class);

    $fixtureLoader->load(['test']);
};

// Ensure that we have "clean" JWT auth cache file
$createJwtAuthCache = static function () {
    // Specify used cache file
    $filename = sprintf(
        '%s%stest_jwt_auth_cache%s.json',
        sys_get_temp_dir(),
        DIRECTORY_SEPARATOR,
        (string) getenv('ENV_TEST_CHANNEL_READABLE')
    );

    // Remove existing cache if exists
    $fs = new Filesystem();
    $fs->remove($filename);
    unset($fs);

    // Create empty cache file
    file_put_contents($filename, '{}');
};

// Create database cache file
$createDatabaseCreateCache = static function () use ($databaseCacheFile, $databaseFilePath) {
    // Create database cache file
    echo 'Backuping Database... '.PHP_EOL;
    $databaseFilePathBackup = $databaseFilePath.'.bak';

    file_put_contents($databaseCacheFile, '{"init": '.(new DateTime())->format(DATE_RFC3339).'}');
    copy($databaseFilePath, $databaseFilePathBackup);
};

// Sempre limpa o cache do JWT
$createJwtAuthCache();

/**
 * Caso a variável de ambiente "FORCE_CREATE_DATABASE" seja passada, este script irá criar
 * um banco de dados mesmo se ele já existir no disco.
 */
$forceCreateDatabase = getenv('FORCE_CREATE_DATABASE');

if (file_exists($databaseFilePath) && filesize($databaseFilePath) > 0) {
    if (!$forceCreateDatabase) {
        return;
    }

    unlink($databaseFilePath);
}

// And finally call each of initialize functions to make test environment ready
array_map(
    '\call_user_func',
    [
        $flushRedisCommand,
        $createSchemaCommand,
        $loadFixturesDoctrineCommand,
        $createDatabaseCreateCache,
    ]
);
