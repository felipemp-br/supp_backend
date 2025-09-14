<?php

declare(strict_types=1);
/**
 * /src/Command/ApiKey/ListApiKeysCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\ApiKey;

use Closure;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ApiKeyResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\ApiKey;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole;
use SuppCore\AdministrativoBackend\Security\RolesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_map;
use function implode;

/**
 * Class ListApiKeysCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'api-key:list', description: 'Console command to list API keys')]
class ListApiKeysCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly RolesService $rolesService,
        private readonly UsuarioResource $usuarioResource
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $io->write("\033\143");

        static $headers = [
            'Id',
            'Token',
            'Username',
            'Nome',
            'Descrição',
            'Roles',
            'Ativo',
        ];

        $io->title('API keys');
        $io->table($headers, $this->getRows());

        return Command::SUCCESS;
    }

    /**
     * Getter method for formatted API key rows for console table.
     */
    private function getRows(): array
    {
        $result = $this->apiKeyResource->getRepository()->findAll();

        return array_map($this->getFormatterApiKey(), $result);
    }

    /**
     * Getter method for API key formatter closure. This closure will format single ApiKey entity for console
     * table.
     */
    private function getFormatterApiKey(): Closure
    {
        return fn (ApiKey|array $apiToken): array => [
            $apiToken->getId(),
            $apiToken->getToken(),
            $this->usuarioResource->findOneBy(['id' => $apiToken->getUsuario()->getId()])->getUserIdentifier(),
            $apiToken->getNome(),
            $apiToken->getDescricao(),
            implode(",\n", $apiToken->getVinculacoesRoles()->map($this->getFormatterVinculacaoRole())->toArray()),
            $apiToken->getAtivo(),
        ];
    }

    /**
     * Getter method for user group formatter closure. This closure will format single VinculacaoRole entity for console
     * table.
     *
     * @return Closure
     */
    private function getFormatterVinculacaoRole(): Closure
    {
        return fn (VinculacaoRole $vinculacaoRole): string => sprintf(
            '%s',
            $vinculacaoRole->getRole()
        );
    }
}
