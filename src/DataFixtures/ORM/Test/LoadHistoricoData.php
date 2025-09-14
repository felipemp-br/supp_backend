<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadHistoricoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Historico;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * Class LoadHistoricoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadHistoricoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $historico = new Historico();
        $historico->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $historico->setDescricao('HISTÓRICO DE PROCESSO');

        // Persist entity
        $manager->persist($historico);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['testHistorico'];
    }
}
