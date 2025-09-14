<?php

declare(strict_types=1);
/**
 * /src/Command/User/DocumentoLimpezaDataHoraValidadeCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Documento;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

/**
 * Class DocumentoLimpezaDataHoraValidadeCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:administrativo:documento:anexoemail',
    description: 'Command para limpeza de documentos anexados nos emails'
)]
class DocumentoLimparEnvioEmailCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * DocumentoLimpezaDataHoraValidadeCommand constructor.
     *
     * @param LoggerInterface     $logger
     * @param DocumentoRepository $documentoRepository
     * @param TransactionManager  $transactionManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly DocumentoRepository $documentoRepository,
        private readonly TransactionManager $transactionManager
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $filesystem = new Filesystem();
        try {
            $outputCode = Command::SUCCESS;
            exec('rm -rf '.sys_get_temp_dir().'/mail_*');
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
            throw $t;
        }
        return $outputCode;
    }
}
