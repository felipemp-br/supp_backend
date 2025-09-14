<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Trait responsável por manipular ações no banco de dados do ambiente de Teste.
 */
trait DatabaseTrait
{
    /**
     * Caminho do arquivo de Banco de dados SQLite utilizado nos testes.
     *
     * @var string
     */
    private string $databasePath = 'var/cache/test/test.db';

    /**
     * Restaura o backup do banco de dados, caso ele exista.
     */
    public function restoreDatabase(): void
    {
        $backupFile = $this->databasePath.'.bak';

        if (file_exists($backupFile)) {
            copy($backupFile, $this->databasePath);
        }
    }

    /**
     * Executa um fixture do Doctrine, de forma incremental (append) no banco de dados atual.
     *
     * @param string[] $groups
     * @param bool     $append
     */
    public function loadFixtures(array $groups, bool $append = true): void
    {
        /** @var FixturesLoader $fixturesLoader */
        $fixturesLoader = $GLOBALS['application']->getKernel()->getContainer()->get(FixturesLoader::class);
        $fixturesLoader->load($groups, $append);
    }
}
