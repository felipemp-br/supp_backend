<?php

declare(strict_types=1);
/**
 * /src/Command/Migration/MigrateVinculacaoRoleCommand.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\TipoRelatorio;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoRelatorioResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Throwable;

/**
 * Class AclTipoRelatorioCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(name: 'tiporelatorio:geraracl', description: 'Gerar ACLs para tipos de relatórios já gerados')]
class AclTipoRelatorioCommand extends Command
{
    // Traits
    use SymfonyStyleTrait;

    /**
     * MigrateVinculacaoRoleCommand constructor.
     *
     * @param TipoRelatorioResource $tipoRelatorioResource
     * @param TransactionManager    $transactionManager
     */
    public function __construct(
        private readonly TipoRelatorioResource $tipoRelatorioResource,
        private readonly TransactionManager $transactionManager,
        private readonly AclProviderInterface $aclProvider
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
     * @return int|null
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $role = $input->getOption('role');

        $tiposRelatorio = $this->tipoRelatorioResource->getRepository()->findAll();

        $outputCode = Command::SUCCESS;
        $progress = new ProgressBar($output, count($tiposRelatorio));
        $progress->setRedrawFrequency(100);
        $io->info('Iniciando processo de inclusão de ACL para TipoRelatorio...');
        $progress->start();

        if (!$tiposRelatorio) {
            throw new \RuntimeException('Não existem tipos de relatorio cadastrados!');
        }

        try {
            foreach ($tiposRelatorio as $entity) {
                $objectIdentity = ObjectIdentity::fromDomainObject($entity);
                try {
                    $acl = $this->aclProvider->findAcl($objectIdentity);
                } catch (Throwable $e) {
                    $acl = $this->aclProvider->createAcl($objectIdentity);
                }

                $securityIdentity = new RoleSecurityIdentity($role);
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_MASTER);
                $this->aclProvider->updateAcl($acl);

                $progress->advance();
            }

            $progress->finish();
            $io->newLine(3);
            $io->success('\n ACL adicionada aos tipos de relatório!');
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
        }

        return $outputCode;
    }
}
