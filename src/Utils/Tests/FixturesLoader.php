<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils\Tests;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function json_last_error;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class FixturesLoader.
 */
class FixturesLoader
{
    private string $referencesCachePath = 'var/cache/test/references.cache';

    private SymfonyFixturesLoader $fixturesLoader;

    private EntityManagerInterface $entityManager;

    private ORMExecutor $ormExecutor;

    /**
     * FixturesLoader constructor.
     *
     * @param SymfonyFixturesLoader  $fixturesLoader
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        SymfonyFixturesLoader $fixturesLoader,
        EntityManagerInterface $entityManager
    ) {
        $this->fixturesLoader = $fixturesLoader;
        $this->entityManager = $entityManager;
    }

    /**
     * Carrega um grupo de fixtures, no banco de dados.
     *
     * @param array $groups
     * @param bool  $append
     *
     * @return void
     */
    public function load(array $groups = [], $append = false): void
    {
        $fixtures = $this->fixturesLoader->getFixtures($groups);

        $consoleOutput = new ConsoleOutput();

        $this->getExecutor()->setLogger(
            static function ($message) use ($consoleOutput): void {
                $consoleOutput->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
            }
        );

        $this->getExecutor()->execute($fixtures, $append);

        if (!$append) {
            $references = $this->getExecutor()->getReferenceRepository()->getReferences();
            $this->cacheReferences($references);
        }

        $this->entityManager->clear();
    }

    /**
     * Cria um cache das referências de entidades utilizadas nesta instância.
     *
     * @param array $references
     *
     * @return bool
     */
    private function cacheReferences(array $references): bool
    {
        $referencesMapped = [];

        foreach ($references as $reference => $object) {
            $isProxy = isset($object->__cloner__);
            $classname = $isProxy ? get_parent_class($object) : get_class($object);
            $pk = $object->getId();
            $referencesMapped[$reference] = compact('classname', 'pk');
        }

        $serialized = json_encode($referencesMapped);

        return false !== file_put_contents($this->referencesCachePath, $serialized);
    }

    /**
     * Carrega o cache das referências de entidades utilizadas em instâncias anteriores.
     *
     * @return array
     */
    private function getCachedReferences(): array
    {
        if (!file_exists($this->referencesCachePath)) {
            return [];
        }

        $serialized = file_get_contents($this->referencesCachePath);
        $arrayReferences = json_decode($serialized, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return [];
        }

        $references = [];
        foreach ($arrayReferences as $refname => $data) {
            $repository = $this->entityManager->getRepository($data['classname']);
            $object = $repository->find($data['pk']);
            $references[$refname] = $object;
        }

        return $references;
    }

    /**
     * Constrói um `ORMExecutor` e retorna sempre a mesma intância.
     *
     * @return ORMExecutor
     */
    private function getExecutor()
    {
        if (isset($this->ormExecutor)) {
            return $this->ormExecutor;
        }

        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $this->ormExecutor = new ORMExecutor($this->entityManager, $purger);

        $cachedReferences = $this->getCachedReferences();
        if (!empty($cachedReferences)) {
            foreach ($cachedReferences as $name => $object) {
                $this->ormExecutor->getReferenceRepository()->addReference($name, $object);
            }
        }

        return $this->ormExecutor;
    }
}
