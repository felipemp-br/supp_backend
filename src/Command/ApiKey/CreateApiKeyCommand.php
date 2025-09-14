<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/CreateApiKeyCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateApiKeyCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:create', description: 'Comando para criar nova API key')]
class CreateApiKeyCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    public function __construct(
        private readonly ApiKeyHelper $apiKeyHelper,
        private readonly ApiKeyResource $apiKeyResource,
        private readonly TransactionManager $transactionManager,
        private readonly UsuarioResource $usuarioResource
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

        $apiKey = new ApiKeyEntity();

        $username = $io->ask('Insira um username para a API key');
        $usuario = $this->usuarioResource->findOneBy(['enabled' => true, 'username' => $username]);

        if (!$usuario) {
            $io->error('Usuário não encontrado!');
            return Command::FAILURE;
        }

        $apiKey->setUsuario($usuario);
        $apiKey->setNome($io->ask('Insira um nome para a API key'));
        $apiKey->setDescricao($io->ask('Insira uma descrição para a API key'));

        $transaction = $this->transactionManager->begin();
        $this->apiKeyResource->save($apiKey, $transaction);
        $this->transactionManager->commit();

        $io->success($this->apiKeyHelper->getApiKeyMessage('API key criada com sucesso', $apiKey));

        return Command::SUCCESS;
    }
}
