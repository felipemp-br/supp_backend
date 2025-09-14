<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/ChangeTokenCommand.php.
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
 * Class ChangeTokenCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:change-token', description: 'Alterar token de uma API key existente')]
class ChangeTokenCommand extends Command
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

        // Get API key entity
        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Qual API key você deseja alterar o token?');

        if ($apiKey instanceof ApiKeyEntity) {
            $message = $this->changeApiKeyToken($apiKey);
        }

        if ($input->isInteractive()) {
            $io->success($message ?? 'O token não foi atualizado');
        }

        return Command::SUCCESS;
    }

    /**
     * Method to change API key token.
     *
     * @throws Exception
     */
    private function changeApiKeyToken(ApiKeyEntity $apiKey): array
    {
        $transaction = $this->transactionManager->begin();

        // Generate new token for API key
        $apiKey->generateToken();

        // Update API key
        $this->apiKeyResource->save($apiKey, $transaction);

        $this->transactionManager->commit();

        return $this->apiKeyHelper->getApiKeyMessage('O token foi atualizado!', $apiKey);
    }
}
