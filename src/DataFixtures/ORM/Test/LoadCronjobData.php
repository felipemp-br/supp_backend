<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadCronjobData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Cronjob;

/**
 * Class LoadCronjobData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCronjobData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $cronjob = new Cronjob();
        $cronjob->setComando('echo cronjob 1');
        $cronjob->setPeriodicidade('*/1 * * * *');
        $cronjob->setNome('Cronjob 1');
        $cronjob->setDescricao('Cronjob 1');
        $cronjob->setSincrono(true);

        // Persist entity
        $manager->persist($cronjob);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
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
        return ['test'];
    }
}
