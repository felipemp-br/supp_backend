<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

/**
 * Alterando Classificação, removendo campo visibilidade_restrita
 */
final class Version20211005142117 extends AbstractMigration
{
    private ContainerInterface $container;

    #[Required]
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDescription(): string
    {
        return 'migration from 1.6.10 do 1.7.0';
    }

    /**
     * @param Schema $schemaFrom
     * @throws ConnectionException
     * @throws Throwable
     */
    public function up(Schema $schemaFrom): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->classificacaoUp($schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }
            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    /**
     * @param Schema $schemaTo
     * @throws SchemaException
     */
    protected function classificacaoUp(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_classificacao');
        $table->dropColumn('visibilidade_restrita');
    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var AclProviderInterface $aclProvider */
        $aclProvider = $this->container->get('security.acl.provider');

        $data = $em->createQueryBuilder()
            ->select('c')
            ->from(Classificacao::class, 'c')
            ->getQuery()->execute();

        $securityIdentity = new RoleSecurityIdentity('ROLE_USER');
        foreach ($data as $classificacao) {
            try {
                $objectIdentity = ObjectIdentity::fromDomainObject($classificacao);
                $acl = $aclProvider->createAcl($objectIdentity);
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_MASTER);
                $aclProvider->updateAcl($acl);
            } catch (Throwable $e) {
                //Já tem ACL...
            }
        }
    }

    /**
     * @param Schema $schemaFrom
     * @throws ConnectionException
     * @throws Throwable
     */
    public function down(Schema $schemaFrom): void
    {
        try {
            $this->connection->beginTransaction();
            $schemaTo = $this->sm->createSchema();
            $this->classificacaoDown($schemaTo);

            // Gerando scripts SQL usando a comparação dos schemas e adicionando ao migration plan
            foreach ($schemaFrom->getMigrateToSql($schemaTo, $this->platform) as $sql) {
                $this->addSql($sql);
            }

            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw  $e;
        }
    }

    public function postDown(Schema $schema): void
    {
        parent::postDown($schema);

        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var AclProviderInterface $aclProvider */
        $aclProvider = $this->container->get('security.acl.provider');

        $data = $em->createQueryBuilder()
            ->select('c')
            ->from(Classificacao::class, 'c')
            ->getQuery()->execute();

        foreach ($data as $classificacao) {
            try {
                $objectIdentity = ObjectIdentity::fromDomainObject($classificacao);
                $acl = $aclProvider->deleteAcl($objectIdentity);
            } catch (Throwable $e) {
                //Não tem ACL...
            }
        }
    }

    /**
     * @param Schema $schemaTo
     * @throws SchemaException
     * @throws Exception
     */
    protected function classificacaoDown(Schema $schemaTo): void
    {
        $table = $schemaTo->getTable('ad_classificacao');
        $table->addColumn(
            'visibilidade_restrita',
            Type::getType('boolean')->getName(),
            [
                'notnull' => true,
                'default' => false,
            ]
        );
    }

    /**
     * @return bool
     */
    public function isTransactional(): bool
    {
        return false;
    }
}
