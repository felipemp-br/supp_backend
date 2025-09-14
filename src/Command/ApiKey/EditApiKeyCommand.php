<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/EditApiKeyCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EditApiKeyCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:edit', description: 'Comando para editar uma API key existente')]
class EditApiKeyCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly ApiKeyHelper $apiKeyHelper,
        private readonly TransactionManager $transactionManager
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Qual API key você deseja editar?');

        if ($apiKey instanceof ApiKeyEntity) {
            $transaction = $this->transactionManager->begin();

            $apiKey->setNome($io->ask('Nome', $apiKey->getNome()));
            $apiKey->setDescricao($io->ask('Descrição', $apiKey->getDescricao()));
            $apiKey->setAtivo((bool) $io->ask('Ativo', (string) $apiKey->getAtivo()));

            $this->apiKeyResource->save($apiKey, $transaction);

            $io->success('API key alterada com sucesso');

            $this->transactionManager->commit();
        }

        return Command::SUCCESS;
    }
}
