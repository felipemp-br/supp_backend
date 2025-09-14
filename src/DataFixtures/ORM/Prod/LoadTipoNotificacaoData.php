<?php

/** @noinspection ProblematicWhitespace */

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadTipoRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao;

/**
 * Class LoadTipoRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoNotificacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tipoNotificacao = new TipoNotificacao();
        $tipoNotificacao->setNome('PROCESSO');
        $tipoNotificacao->setDescricao('PROCESSO');
        $manager->persist($tipoNotificacao);

        $tipoNotificacao = new TipoNotificacao();
        $tipoNotificacao->setNome('TAREFA');
        $tipoNotificacao->setDescricao('TAREFA');
        $manager->persist($tipoNotificacao);

        $tipoNotificacao = new TipoNotificacao();
        $tipoNotificacao->setNome('RELATORIO');
        $tipoNotificacao->setDescricao('RELATORIO');
        $manager->persist($tipoNotificacao);

        $tipoNotificacao = $manager
            ->createQuery(
                "SELECT tn 
        FROM SuppCore\AdministrativoBackend\Entity\TipoNotificacao tn 
        WHERE tn.nome = 'ASSINATURA'"
            )
            ->getOneOrNullResult() ?: new TipoNotificacao();
        $tipoNotificacao->setNome('ASSINATURA');
        $tipoNotificacao->setDescricao('ASSINATURA');
        $manager->persist($tipoNotificacao);

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test', '1.4.0'];
    }
}
