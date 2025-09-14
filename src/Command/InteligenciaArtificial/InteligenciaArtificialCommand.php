<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\InteligenciaArtificial;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts\ClientContext;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\SimplePrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Services\InteligenciaArtificialService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

/**
 * InteligenciaArtificialCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:ia:completions', description: 'Testa Configuração da IA')]
class InteligenciaArtificialCommand extends Command
{
    /**
     * Constructor.
     *
     * @param InteligenciaArtificialService $inteligenciaArtificialService
     */
    public function __construct(
        private readonly InteligenciaArtificialService $inteligenciaArtificialService,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addArgument(
            'prompt',
            InputArgument::OPTIONAL,
            'Prompt opcional'
        );
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
        try {
            $client = $this->inteligenciaArtificialService->getClient();
            $text = $input->getArgument('prompt') ?? 'Quem é o campeão da copa do mundo de futebol de 1970?';
            $io->info(
                sprintf(
                    'Driver: %s '.PHP_EOL.'Modelo %s '.PHP_EOL.'Prompt: %s',
                    $client->getDriver(),
                    $client->getCompletionsModel(),
                    $text
                )
            );
            $client->setClientContext(
                new ClientContext(
                    self::class
                )
            );
            $response = $client->getCompletions(
                new SimplePrompt($text),
                maxTokens: 250
            );
            $io->success(
                sprintf(
                    'Resposta: '.PHP_EOL.PHP_EOL.'"%s"'.PHP_EOL.PHP_EOL
                    .'Prompt Tokens: %s, Completions Tokens: %s, Total Tokens: %s',
                    $response->getResponse(),
                    $response->getPromptTokens(),
                    $response->getCompletionsTokens(),
                    $response->getTotalTokens(),
                )
            );

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
