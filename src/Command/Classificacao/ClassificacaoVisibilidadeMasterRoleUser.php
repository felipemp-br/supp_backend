<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\Classificacao;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Throwable;

/**
 * Class ClassificacaoVisibilidadeMasterRoleUser.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsCommand(
    name: 'supp:classificacao:visibilidade:maste:role_user',
    description: 'Command para adição da ROLE_USER como MASTER para todas as classificações'
)
]
class ClassificacaoVisibilidadeMasterRoleUser extends Command
{
    use SymfonyStyleTrait;

    /**
     * @param LoggerInterface       $logger
     * @param ClassificacaoResource $classificacaoResource
     * @param AclProviderInterface  $aclProvider
     * @param TransactionManager    $transactionManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ClassificacaoResource $classificacaoResource,
        private readonly AclProviderInterface $aclProvider,
        private readonly TransactionManager $transactionManager
    ) {
        parent::__construct();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $classificacoes = $this->classificacaoResource->getRepository()->findAll();
        $outputCode = Command::SUCCESS;
        $progress = new ProgressBar($output, count($classificacoes));
        $progress->setRedrawFrequency(100);
        $io->info('Iniciando processo de alteração de ACL da Classificação...');
        $progress->start();

        try {
            foreach ($classificacoes as $classificacao) {
                $objectIdentity = ObjectIdentity::fromDomainObject($classificacao);
                try {
                    $this->aclProvider->deleteAcl($objectIdentity);
                } catch (Throwable $e) {
                }

                $acl = $this->aclProvider->createAcl($objectIdentity);
                $securityIdentity = new RoleSecurityIdentity('ROLE_USER');
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_MASTER);
                $this->aclProvider->updateAcl($acl);

                $progress->advance();
            }

            $progress->finish();
            $io->newLine(3);
            $io->success('\n Processo finalizado com sucesso!');
        } catch (Throwable $t) {
            $outputCode = Command::FAILURE;
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
        }

        return $outputCode;
    }
}
