<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\CircuitBreaker;

use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakStatus;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CircuitBreakerCheckCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:circuit_breaker:check',
    description: 'Verifica o status dos circuitos. '
)]
class CircuitBreakerCheckCommand extends Command
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
        $this->addArgument('service_key', InputArgument::OPTIONAL, 'Key do serviço a ser verificado.');
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
            $circuitBreak = $this->circuitBreakerService->getCircuitBreak($serviceKey);
            $status = $this->circuitBreakerService->getCircuitBreakStatus($circuitBreak);
            $this->outputStatus(
                $io,
                $status
            );
        } else {
            foreach ($this->circuitBreakerService->getResources() as $resource) {
                foreach ($resource->getServicesKeys() as $servicesKey) {
                    $this->outputStatus(
                        $io,
                        $this->circuitBreakerService->getCircuitBreakStatus($servicesKey)
                    );
                }
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param SymfonyStyle       $io
     * @param CircuitBreakStatus $status
     *
     * @return void
     */
    private function outputStatus(
        SymfonyStyle $io,
        CircuitBreakStatus $status
    ): void {
        $io->success(
            sprintf(
                'SERVICE KEY: %s, STATUS: %s, FAILURES COUNT: %s, REMAINING OPEN TIME: %s second(s).',
                $status->getServiceKey(),
                $status->isOpen() ? 'open' : 'closed',
                $status->getFailuresCount(),
                $status->getRemainingOpenTime(),
            )
        );
    }
}
