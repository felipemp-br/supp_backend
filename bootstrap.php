<?php

declare(strict_types=1);
/**
 * Application bootstrap file to load specified environment variables.
 *
 * @see ./public/index.php
 * @see ./tests/bootstrap.php
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
use Symfony\Component\Dotenv\Dotenv;

$environmentFile = (string) \getenv('ENVIRONMENT_FILE');
$readableChannel = (string) \getenv('ENV_TEST_CHANNEL_READABLE');

// Application is started against 'fastest' library, so we need to override database name manually
if (\strlen($readableChannel) > 0) {
    // Parse current '.env.test' file
    $variables = (new Dotenv())->parse(\file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$environmentFile));

    // Specify new database name for current test env
    $databaseName = $variables['DATABASE_NAME'].'_'.$readableChannel;

    // Replace DATABASE_URL variable
    $variables['DATABASE_URL'] = \str_replace(
        '/'.$variables['DATABASE_NAME'].'?',
        '/'.$databaseName.'?',
        $variables['DATABASE_URL']
    );

    // Replace DATABASE_NAME variable
    $variables['DATABASE_NAME'] = $databaseName;

    // And finally populate new variables to current environment
    (new Dotenv())->populate($variables);
} else {
    // Load environment variables normally
    (new Dotenv())->load(__DIR__.DIRECTORY_SEPARATOR.$environmentFile);
}
