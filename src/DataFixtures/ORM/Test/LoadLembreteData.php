<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadLembreteData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Lembrete;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * Class LoadLembreteData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadLembreteData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $lembrete = new Lembrete();
        $lembrete->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $lembrete->setConteudo('LEMBRETE 1');

        // Persist entity
        $manager->persist($lembrete);

        $lembrete = new Lembrete();
        $lembrete->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $lembrete->setConteudo('LEMBRETE 2');

        // Persist entity
        $manager->persist($lembrete);

        $lembrete = new Lembrete();
        $lembrete->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $lembrete->setConteudo('LEMBRETE 3');

        // Persist entity
        $manager->persist($lembrete);

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
        return ['testLembrete'];
    }
}
