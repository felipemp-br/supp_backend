<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\CircuitBreaker;

use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CircuitBreakerCloseCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:circuit_breaker:close',
    description: 'Fecha o circuito permitindo que os serviços voltem a funcionar. '
)]
class CircuitBreakerCloseCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    /**
     * Constructor.
     *
     * @param CircuitBreakerService $circuitBreakerService
     */
    public function __construct(
        private readonly CircuitBreakerService $circuitBreakerService
    ) {
        parent::__construct();
        $this->addArgument('service_key', InputArgument::OPTIONAL, 'Key do serviço a ser fechado.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if ($input->getArgument('service_key')) {
            $serviceKey = $input->getArgument('service_key');
            $this->circuitBreakerService->closeCircuit($serviceKey);
            $io->success(
                sprintf(
                    'O service key %s esta com o circuito fechado.',
                    $input->getArgument('service_key'),
                )
            );
        } else {
            $this->circuitBreakerService->closeAllCircuits();
            $io->success('Todos os circuitos fechados.');
        }

        return Command::SUCCESS;
    }
}
