<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Processo;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;

/**
 * Class ProcessoVinculacaoCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:administrativo:processo:vinculacao:fix',
    description: 'Command para corrigir vinculação de processos criados de ofício'
)]
class ProcessoVinculacaoCommand extends Command
{
    use SymfonyStyleTrait;

    /**
     * ProcessoVinculacaoCommand constructor.
     *
     * @param LoggerInterface                       $logger
     * @param VinculacaoProcessoRepository          $vinculacaoProcessoRepository
     * @param VinculacaoProcessoResource            $vinculacaoProcessoResource
     * @param TransactionManager                    $transactionManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        private readonly VinculacaoProcessoResource $vinculacaoProcessoResource,
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
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Throwable
     * @throws AnnotationException
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $rows = $this->vinculacaoProcessoRepository->findAllVinculacoesToFix();
        $outputCode = Command::SUCCESS;
        $totalVinculacoes = 0;

        try {
            
            if ($rows) {
                $transactionId = $this->transactionManager->begin();
                $io->info('Mapeando ' . count($rows) . ' registro(s).');

                $progress = $io->createProgressBar(count($rows));
                $processar = [];

                /* @var VinculacaoProcesso $vinculacao */
                foreach ($rows as $key => $row) {
                    $vinculoFinal = $row;
                    $vinculos = [];
                    do {
                        $vinculos = array_filter($rows, fn($r) => $r['vpr_processo_id'] === $vinculoFinal['vpp_processo_id']);
                        if(count($vinculos) > 0) {
                            $vinculoFinal = current($vinculos);
                        }
                    } while(count($vinculos) > 0);
   
                    if(!isset($processar[$vinculoFinal['vpp_processo_id']])) {
                        $processar[$vinculoFinal['vpp_processo_id']] = [];
                    }
                    array_push($processar[$vinculoFinal['vpp_processo_id']], $row['vpr_id']);
                    
                    $progress->advance();
                }

                $progress->finish();

                $table = new Table($output);
                $table->setHeaders(['Vinculações', 'Processo Principal']);
                foreach($processar as $processoId => $vinculacoesIds) {
                    $table->addRow([
                        implode(',', $vinculacoesIds),
                        $processoId
                    ]);
                }
                $io->newLine(2);
                $table->render();

                /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Total de ' . count($processar) . ' registro(s). Deseja continuar? (s,N)  ', false, '/^s/i');

                if(!$helper->ask($input, $output, $question)) {
                    return $outputCode;
                }

                $io->newLine(2);

                $io->info('Processando ' . count($processar) . ' bloco(s) de registro(s)');

                $progress = $io->createProgressBar(count($processar));

                foreach($processar as $processoId => $vinculacoesIds) {
                    $totalVinculacoes += $this->vinculacaoProcessoRepository->updateVinculacoesToFix($processoId, $vinculacoesIds);
                    $progress->advance();
                }
                $progress->finish();

                $io->newLine(2);

                $this->transactionManager->commit($transactionId);
            } else {
                $message = 'Nenhum registro para ser corrigido!';
            }
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
            throw $t;
        }

        if ($input->isInteractive()) {
            $io->newLine();
            $io->success($message ?? 'Total de '.$totalVinculacoes.' vinculação(ões) corrigida(s).');
        }

        return $outputCode;
    }
}
