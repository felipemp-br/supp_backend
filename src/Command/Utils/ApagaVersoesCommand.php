<?php

declare(strict_types=1);
/**
 * /src/Command/Utils/ApagaVersoesCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Utils;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Class ApagaVersoesCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:componentesdigitais:apagaversoes',
    description: 'Apaga versoes antigas dos componentes digitais juntados ha 6 meses'
)]
class ApagaVersoesCommand extends Command
{
    /**
     * ApagaVersoesCommand constructor.
     *
     * @param EntityManagerInterface    $em
     * @param ParameterBagInterface     $parameterBag
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ParameterBagInterface $parameterBag,
        private readonly ComponenteDigitalResource $componenteDigitalResource
    ) {
        parent::__construct();
        $this->addOption('batch', null, InputOption::VALUE_OPTIONAL, 'Qual o tamanho do batch (default 100)?');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batch = $input->getOption('batch');

        if (!$batch) {
            $batch = 1_000;
        }

        $output->writeln((string) $this->processa($batch, $output));

        return Command::SUCCESS;
    }

    /**
     * @param                 $batch
     * @param OutputInterface $output
     *
     * @return bool
     */
    private function processa($batch, OutputInterface $output): bool
    {
        try {
            $filesystem = $this->parameterBag->get('supp_core.administrativo_backend.filesystem_directory');
            $conn = $this->em->getConnection();
            $terminou = false;

            $hoje = new DateTime();
            $seisMesesAtras = $hoje->modify('-6 months');

            while (!$terminou) {
                // esse comando só roda entre as 3h e as 5h da manhã
                $agora = new DateTime();
                if ($agora->format('H') < 3 || $agora->format('H') > 5) {
                    break;
                }

                $result = $this->componenteDigitalResource->getRepository()->findVersaoComponjenteDigital($seisMesesAtras, $batch);

                if (isset($result[0])) {
                    $memory = round(memory_get_usage() / (1_024 * 1_024)); // to get usage in Mo
                    $memoryMax = round(memory_get_peak_usage() / (1_024 * 1_024)); // to get max usage in Mo

                    $output->writeln('processando '.$batch.' componentes digitais... '.sprintf(' (RAM : current=%uMo peak=%uMo)', $memory, $memoryMax));

                    /** @var ComponenteDigital $aComponenteDigital */
                    foreach ($result as $aComponenteDigital) {
                        try {
                            // o atual apontador está corrompido? aborta
                            if (!isset($aComponenteDigital['hash'])
                                || !$filesystem->has($aComponenteDigital['hash'])) {
                                continue;
                            }

                            // recurpera as versões
                            $queryLogs = $this->em->createQuery(
                                'SELECT log FROM Gedmo\Loggable\Entity\LogEntry log
                                WHERE log.objectId = :objectId
                                AND log.objectClass = :objectClass
                                ORDER BY log.version DESC'
                            );
                            $queryLogs->setParameter('objectId', $aComponenteDigital['id']);
                            $queryLogs->setParameter('objectClass', 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital');

                            $logs = $queryLogs->getResult();

                            // só tem versões se a quantidade de logs for maior que 1
                            if (count($logs) > 1) {
                                /** @var LogEntry $log */
                                foreach ($logs as $log) {
                                    $dataLog = $log->getData();

                                    if (isset($dataLog['hash'])) {
                                        // descarta a atual
                                        if ($aComponenteDigital['hash'] == $dataLog['hash']) {
                                            continue;
                                        }

                                        $qtdComponenteDigitalIdentico = $this->componenteDigitalResource->getRepository()->findCountByHash($dataLog['hash']);

                                        // somente apaga se não houver outro componente digital com esse hash
                                        if (('0' === $qtdComponenteDigitalIdentico)
                                            && $filesystem->has($dataLog['hash'])) {
                                            $filesystem->delete($dataLog['hash']);
                                            $output->writeln('apagado: id '.$aComponenteDigital['id'].' hash '.$dataLog['hash']);
                                        }
                                    }
                                }
                            }

                            // marca a limpeza, para não ficar voltando no comando
                            // TODO Verificar o update pois o campo versao_eliminadas não existe mais
                            $conn->executeUpdate('UPDATE componentes_digitais SET versoes_eliminadas = 1 WHERE id = ?', [$aComponenteDigital['id']]);
                        } catch (Throwable $e) {
                            $output->writeln('erro: '.$e->getMessage());
                        }
                    }

                    $this->em->clear();
                } else {
                    $terminou = true;
                }
            }
        } catch (Throwable $e) {
            $output->writeln('erro: '.$e->getTraceAsString());
        }

        $output->writeln('FIM');

        return true;
    }
}
