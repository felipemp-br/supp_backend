<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Utils;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionProperty;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * Class AppendCommand.
 */
#[AsCommand(name: 'supp:append', description: 'Console command to list API keys')]
class AppendCommand extends Command
{
    private Parser $yamlParser;

    private array $filesToLoad = [];

    private array $dataToPersist = [];

    private int $totalToPersist = 0;

    private array $referredClasses = [];

    private array $filesLoaded = [];

    private array $dataPersisted = [];

    private array $entityPersisted = [];

    private bool $info = false;

    private bool $ignoraEntity = false;

    private SymfonyStyle $io;

    /** @interval */
    public const REGEX = '/.{1,}\.ya?ml$/i';

    /**
     * AppendFixtureCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface  $parameters
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ParameterBagInterface $parameters
    ) {
        parent::__construct();
        $this->yamlParser = new Parser();

        $this->addOption('altDirs', 'd', InputOption::VALUE_REQUIRED, 'Lista opcional de diretórios com arquivos YML que serão carregados, separados por vírgula')
            ->addOption('refDirs', 'r', InputOption::VALUE_REQUIRED, 'Lista opcional de diretórios com arquivos YML de referência, separados por vírgula')
            ->addOption('refClasses', 'c', InputOption::VALUE_REQUIRED, 'Lista opcional de classes de referência cuja desidentificação do localizador será feita pela sua posição relativa no arquivo.')
            ->addOption('info', 'i', InputOption::VALUE_NONE, 'Informações sobre o processamento.')
            ->addOption('ignoraEntity', 'g', InputOption::VALUE_NONE, 'Ignora entity não encontrada na hora de persistir.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->info = $input->getOption('info');
        $this->ignoraEntity = $input->getOption('ignoraEntity');

        $this->referredClasses = [];
        if ($input->getOption('refClasses', null)) {
            $this->referredClasses = explode(',', $input->getOption('refClasses', ''));
        }

        $altDirs = $input->getOption('altDirs');
        $altDirs = $altDirs ? explode(',', $altDirs) : [''];

        $filesToLoad = $this->locateFiles($altDirs);
        $this->dataToPersist = [];
        $this->totalToPersist = 0;
        foreach ($filesToLoad as $file) {
            $parsedData = $this->parse($file);
            $numEntries = is_array($parsedData) && is_array($parsedData[array_keys($parsedData)[0]]) ? count($parsedData[array_keys($parsedData)[0]]) : 0;
            $this->filesToLoad[$file] = $numEntries;
            $this->dataToPersist = [...$this->dataToPersist, ...$parsedData];
            $this->totalToPersist += $numEntries;
        }

        $refDirs = $input->getOption('refDirs');
        $refDirs = $refDirs ? explode(',', $refDirs) : [''];

        $filesLoaded = $this->locateFiles($refDirs);
        $this->dataPersisted = [];
        foreach ($filesLoaded as $file) {
            $parsedData = $this->parse($file);
            $this->filesLoaded[$file] = is_array($parsedData) && is_array($parsedData[array_keys($parsedData)[0]]) ? count($parsedData[array_keys($parsedData)[0]]) : 0;
            $this->dataPersisted = [...$this->dataPersisted, ...$parsedData];
        }

        if ($this->info) {
            printf("Files Loaded (%d):\n[\n", count($this->filesLoaded));
            array_walk($this->filesLoaded, function ($value, $key) {
                printf("[X] File %s: %d entries\n", $key, $value);
            });

            printf("]\nFiles To Load (%d):\n[\n", count($this->filesToLoad));
            array_walk($this->filesToLoad, function ($value, $key) {
                printf("[X] File %s: %d entries\n", $key, $value);
            });
            echo "]\nTotal to persist: {$this->totalToPersist}\n";
        }
        $this->persistData();

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    public function persistData(): void
    {
        // loop - first level - entities
        foreach ($this->dataToPersist as $entityClassName => $entityData) {
            // test if file is empty (no data)
            if ($entityData) {
                // loop - second level - entity data
                foreach ($entityData as $entityReference => $entityDatum) {
                    try {
                        $this->persistEntity($entityClassName, $entityReference, $entityDatum);
                    } catch (Throwable $t) {
                        echo $t->getMessage();
                        echo $t->getTraceAsString();
                        var_dump($entityDatum);
                    }
                    --$this->totalToPersist;
                    if ($this->info) {
                        echo "[X] Total to persist: {$this->totalToPersist}\n";
                    }
                }
            }
        }
    }

    /**
     * @param $entityClassName
     * @param $entityReference
     * @param $entityDatum
     *
     * @return object
     *
     * @throws Exception
     */
    public function persistEntity($entityClassName, $entityReference, $entityDatum): object
    {
        // verifica se a entity ja foi persistida
        if (isset($this->entityPersisted[$entityReference])) {
            if ($this->info) {
                echo "Encontrada a entity a ser persistida. Desnecessidade. Entity $entityClassName::$entityReference\n";
            }

            return $this->entityPersisted[$entityReference];
        }

        $setterSelf = false;
        $entity = new $entityClassName();
        foreach ($entityDatum as $property => $value) {
            $setterMethod = 'set'.ucfirst($property);
            if (method_exists($entity, $setterMethod)) {
                if (is_string($value) && '@self' == trim($value)) {
                    $setterSelf = $setterMethod;
                    $value = null;
                }

                if (is_string($value) && preg_match('/^@(.*)$/', trim($value), $matches)) {
                    $newEntityReference = trim($matches[1]);
                    $newEntity = null;

                    // a persistir
                    list($newEntityClassName, $newEntityDatum) = $this->getToPersistDatumByreference($newEntityReference);
                    if ($newEntityDatum) {
                        if ($this->info) {
                            echo "Encontrado array de dados de entidade a persistir $newEntityClassName::$newEntityReference\n";
                        }
                        $newEntity = $this->persistEntity($newEntityClassName, $newEntityReference, $newEntityDatum);
                    }

                    // persistidos
                    else {
                        list($newEntityClassName, $newEntityDatum) = $this->getPersistedDatumByreference($newEntityReference);
                        if ($newEntityDatum) {
                            if ($this->info) {
                                echo "Encontrado array de dados de entidade persistida $newEntityClassName::$newEntityReference\n";
                            }
                            $newEntity = $this->getPersistedEntityByReference($newEntityReference);
                        }
                    }
                    // entity referida encontrada
                    if ($newEntity) {
                        $value = $newEntity;
                    }

                    // entity referida nao encontrada
                    else {
                        // ignora
                        if ($this->ignoraEntity) {
                            continue;
                        }

                        // levanta excecao
                        throw new Exception("Entity referida não encontrada ($newEntityReference). Impossível criar entity $entityClassName::$entityReference\n");
                    }
                }

                if (is_string($value) && preg_match('/\d{2}\/\d{2}\/\d{4}( \d{2}:\d{2}:\d{2})?/', trim($value), $matches)) {
                    $format = count($matches) > 1 ? 'd/m/Y H:i:s' : 'd/m/Y';
                    $value = DateTime::createFromFormat($format, $matches[0]);
                }

                if (is_string($value) && preg_match('/\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?/', trim($value), $matches)) {
                    $format = count($matches) > 1 ? 'Y-m-d H:i:s' : 'Y-m-d';
                    $value = DateTime::createFromFormat($format, $matches[0]);
                }

                $parameterType = null;
                $reflection = new ReflectionProperty($entityClassName, $property);
                if (preg_match('/@var\s+([^\s]+)/', $reflection->getDocComment(), $matches)) {
                    $parameterType = $matches[1];
                }

                if ($parameterType && (false !== strpos($parameterType, 'string')) && !is_string($value) && (null !== $value)) {
                    $value = strval($value);
                }

                try {
                    $entity->$setterMethod($value);
                } catch (Throwable $t) {
                    echo "$setterMethod\n";
                    var_dump($entityDatum);
                    exit;
                }
            } else {
                throw new Exception("Método $setterMethod não encontrado na classe $entityClassName.");
            }
        }
        if ($this->info) {
            echo "Persistindo  $entityClassName::$entityReference\n";
        }

        $this->em->persist($entity);
        $this->em->flush();
        if ($setterSelf) {
            $entity->$setterSelf($entity->getId());
            $this->em->persist($entity);
            $this->em->flush();
        }

        // retira da lista para persistir
        unset($this->dataToPersist[$entityClassName][$entityReference]);

        // coloca na lista dos persistidos
        $this->dataPersisted[$entityClassName][$entityReference] = $entityDatum;

        // coloca no cache
        $this->entityPersisted[$entityReference] = $entity;

        return $entity;
    }

    /**
     * @param string $reference
     *
     * @return array
     */

    /**
     * @param string $reference
     *
     * @return array
     */
    public function getToPersistDatumByreference(string $reference): array
    {
        if ($this->info) {
            echo "Pesquisa de array de dados de entity a persistir $reference\n";
        }
        foreach ($this->dataToPersist as $entityClassName => $entityData) {
            if ($entityData && isset($entityData[$reference])) {
                return [$entityClassName, $entityData[$reference]];
            }
        }

        return [null, null];
    }

    /**
     * @param string $reference
     *
     * @return array
     */

    /**
     * @param string $reference
     *
     * @return array
     */
    public function getPersistedDatumByreference(string $reference): array
    {
        if ($this->info) {
            echo "Pesquisa de array de dados de entity persistida $reference\n";
        }

        foreach ($this->dataPersisted as $entityClassName => $entityData) {
            if ($entityData && isset($entityData[$reference])) {
                return [$entityClassName, $entityData[$reference]];
            }
        }

        return [null, null];
    }

    /**
     * @param string $reference
     *
     * @return int
     */
    public function getPersistedIdByReference(string $reference): int
    {
        foreach ($this->dataPersisted as $entityClassName => $entityData) {
            if ($entityData && isset($entityData[$reference])) {
                $index = array_search($reference, array_keys($entityData));

                return (false === $index) ? -1 : $index + 1;
            }
        }

        return -1;
    }

    /**
     * @param string $reference
     *
     * @return object|null
     *
     * @throws Exception
     */
    public function getPersistedEntityByReference(string $reference): ?object
    {
        if ($this->info) {
            echo "Pesquisa de entity persistida $reference\n";
        }

        // cache
        if (isset($this->entityPersisted[$reference])) {
            return $this->entityPersisted[$reference];
        }

        // pesquisa
        list($entityClassName, $entityDatum) = $this->getPersistedDatumByreference($reference);

        $entity = new $entityClassName();
        if ($entityDatum) {
            $criteriaArray = [];
            foreach ($entityDatum as $property => $value) {
                if (!property_exists($entity, $property)) {
                    continue;
                }

                $reflection = new ReflectionProperty($entityClassName, $property);
                if (!preg_match('/@\S*@ORM\\\Table\S*/', $reflection->getDocComment(), $matches)) {
                    continue;
                }

                if (('' === $value) || (null === $value)) {
                    continue;
                }

                if (is_string($value) && '@self' == trim($value)) {
                    continue;
                }

                if (is_string($value) && preg_match('/^@(.*)$/', trim($value), $matches)) {
                    $newEntityReference = trim($matches[1]);
                    $value = $this->getPersistedEntityByReference($newEntityReference);
                    if (null === $value) {
                        continue;
                    }
                }

                if (is_string($value) && preg_match(
                    '/\d{2}\/\d{2}\/\d{4}( \d{2}:\d{2}:\d{2})?/',
                    trim($value),
                    $matches
                )) {
                    $format = count($matches) > 1 ? 'd/m/Y H:i:s' : 'd/m/Y';
                    $value = DateTime::createFromFormat($format, $value);
                }

                if (is_string($value) && preg_match(
                    '/\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?/',
                    trim($value),
                    $matches
                )) {
                    $format = count($matches) > 1 ? 'Y-m-d H:i:s' : 'Y-m-d';
                    $value = DateTime::createFromFormat($format, $value);
                }
                $criteriaArray[$property] = $value;
            }

            // pesquisa pelos campos
            if ($this->info) {
                echo "Pesquisando por array de pesquisa ($entityClassName).\n";
            }
            $result = $this->em->getRepository($entityClassName)->findBy($criteriaArray);
            if (count($result) > 0) {
                return $this->entityPersisted[$reference] = $result[0];
            }

            // pesquisa pela posicao relativa
            $entityClassNameSimplified = substr(strrchr($entityClassName, '\\'), 1);
            $referredClassesSimplified = array_map(fn ($s) => false === strrchr($s, '\\') ? $s : substr(strrchr($s, '\\'), 1), $this->referredClasses);
            if (count($this->referredClasses) && in_array($entityClassNameSimplified, $referredClassesSimplified)) {
                if ($this->info) {
                    echo "Pesquisando por posição relativa de referência ($entityClassName).\n";
                }

                $id = $this->getPersistedIdByReference($reference);
                if ($id >= 0) {
                    $criteriaArray = ['id' => $id];
                    $result = $this->em->getRepository($entityClassName)->findBy($criteriaArray);
                    if (count($result) > 0) {
                        return $this->entityPersisted[$reference] = $result[0];
                    }
                }
            }
        }
        if ($this->info) {
            echo "Não encontrado($entityClassName).\n";
        }

        return null;
    }

    /**
     * @param string $file
     *
     * @return array
     *
     * @throws Exception
     */
    public function parse(string $file): array
    {
        if (false === file_exists($file)) {
            throw new Exception("Arquivo não encontrado ($file).");
        }

        $data = defined('Symfony\\Component\\Yaml\\Yaml::PARSE_CONSTANT')
            ? $this->yamlParser->parse(file_get_contents($file), Yaml::PARSE_CONSTANT)
            : $this->yamlParser->parse(file_get_contents($file));

        return (null === $data) ? [] : $data;
    }

    /**
     * @param array  $dirs
     * @param string $environment
     *
     * @return array
     */
    public function locateFiles(array $dirs, string $environment = null): array
    {
        $baseDir = $this->parameters->get('kernel.project_dir');
        $fixtureFiles = [...array_map(
            fn (string $dir): array => $this->doLocateFiles($baseDir, $dir, $environment),
            $dirs
        )];

        return $fixtureFiles;
    }

    /**
     * @param string      $baseDir
     * @param string      $dir
     * @param string|null $environment
     *
     * @return array
     */
    private function doLocateFiles(string $baseDir, string $dir, string $environment = null): array
    {
        $fixtureDirs = ['fixtures'];
        $fullPaths = array_filter(
            array_map(
                fn (string $fixtureDir): string => '' !== $environment
                    ? sprintf('%s/%s/%s/%s', $baseDir, $fixtureDir, $dir, $environment)
                    : sprintf('%s/%s/%s', $baseDir, $fixtureDir, $dir),
                $fixtureDirs
            ),
            fn ($fullPath) => $fullPath && file_exists($fullPath)
        );

        if ([] === $fullPaths) {
            return [];
        }

        $files = SymfonyFinder::create()->files()->in($fullPaths)->depth(0)->name('/.*\.(ya?ml|php)$/i');
        $files = $files->sort(
            fn (SplFileInfo $a, SplFileInfo $b) => strcasecmp($a->getFilename(), $b->getFilename())
        );

        $fixtureFiles = [];
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $fixtureFiles[$file->getRealPath()] = true;
        }

        return array_keys($fixtureFiles);
    }
}
