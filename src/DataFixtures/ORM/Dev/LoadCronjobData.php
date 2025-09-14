<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Cronjob;

/**
 * Class LoadCredorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCronjobData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $cronjob1 = new Cronjob();
        $cronjob1->setComando('/usr/bin/php bin/console supp:administrativo:datalake:kafka --onlyOnce');
        $cronjob1->setSincrono(false);
        $cronjob1->setPeriodicidade('10 * * * *');
        $cronjob1->setNome('Busca dossiês do Datalake');
        $cronjob1->setDescricao('Realiza a verificação de dados prontos junto ao Datalake');
        $cronjob1->setAtivo(true);
        $manager->persist($cronjob1);
        $this->addReference(
            'Cronjob-'.$cronjob1->getNome(),
            $cronjob1
        );

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
        return 6;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return [
            'dev', 'test',
            'cronjob-datalake-prod',
            'cronjob-datalake-dev',
            'cronjob-datalake-test',
        ];
    }
}
