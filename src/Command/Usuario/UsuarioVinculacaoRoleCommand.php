<?php

declare(strict_types=1);
/**
 * /src/Command/Migration/MigrateVinculacaoRoleCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Usuario;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRole;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRoleResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UsuarioVinculacaoRoleCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'supp:usuario-vinculacao-role', description: 'Concede uma role a um usuario')]
class UsuarioVinculacaoRoleCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    /**
     * MigrateVinculacaoRoleCommand constructor.
     *
     * @param VinculacaoRoleResource $vinculacaoRoleResource
     * @param TransactionManager     $transactionManager
     * @param UsuarioRepository      $usuarioRepository
     */
    public function __construct(
        private readonly VinculacaoRoleResource $vinculacaoRoleResource,
        private readonly TransactionManager $transactionManager,
        private readonly UsuarioRepository $usuarioRepository
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'username',
            'u',
            InputOption::VALUE_REQUIRED,
            'Username do usuario'
        )->addOption(
            'type',
            't',
            InputOption::VALUE_REQUIRED,
            'Se a role eventualmente já existente deve ser removida'
        )->addOption(
            'role',
            'r',
            InputOption::VALUE_REQUIRED,
            'Role a ser concedida'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        if ('promote' !== $input->getOption('type')
            && 'demote' !== $input->getOption('type')) {
            throw new RuntimeException('Argument type deve ser promote ou demote!');
        }

        /** @var Usuario $usuario */
        $usuario = $this->usuarioRepository->findOneBy(['username' => $input->getOption('username')]);
        $role = $input->getOption('role');

        if (!$usuario) {
            throw new RuntimeException('Usuário não localizado!');
        }

        if ('ROLE_ROOT' === $role) {
            throw new RuntimeException('Role Root não pode ser concedida!');
        }

        if ('ROLE_ADMIN' === $role) {
            throw new RuntimeException('Role Admin não pode ser concedida a usuarios externos!');
        }

        $vinculacaoRole = $this->vinculacaoRoleResource->getRepository()->findOneBy(
            ['role' => $role, 'usuario' => $usuario->getId()]
        );

        if ('promote' === $input->getOption('type')) {
            if ($vinculacaoRole) {
                throw new RuntimeException('Usuário já possui essa role!');
            }

            $transactionId = $this->transactionManager->begin();
            $vinculacaoRoleDTO = new VinculacaoRole();
            $vinculacaoRoleDTO->setRole($role);
            $vinculacaoRoleDTO->setUsuario($usuario);
            $this->vinculacaoRoleResource->create($vinculacaoRoleDTO, $transactionId);
            $this->transactionManager->commit($transactionId);

            if ($input->isInteractive()) {
                $io->success('Role concedida');
            }

            return self::SUCCESS;
        } else {
            if (!$vinculacaoRole) {
                throw new RuntimeException('Usuário não possui essa role!');
            }

            $transactionId = $this->transactionManager->begin();
            $this->vinculacaoRoleResource->delete($vinculacaoRole->getId(), $transactionId);
            $this->transactionManager->commit($transactionId);
            if ($input->isInteractive()) {
                $io->success('Role removida com sucesso');
            }

            return self::SUCCESS;
        }
    }
}
