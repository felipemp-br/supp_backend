<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadTipoDossieData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\TipoDossie;

/**
 * Class LoadTipoDossieData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoDossieData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tipoDossie = new TipoDossie();
        $tipoDossie->setFonteDados('FONTE DE DADOS');
        $tipoDossie->setPeriodoGuarda(100);
        $tipoDossie->setNome('ADMINISTRATIVO');
        $tipoDossie->setSigla('ADM');
        $tipoDossie->setDescricao('DOSSIÊ ADMINISTRATIVO');
        $tipoDossie->setAtivo(true);

        // Persist entity
        $manager->persist($tipoDossie);

        $tipoDossie = new TipoDossie();
        $tipoDossie->setFonteDados('FONTE DE DADOS');
        $tipoDossie->setPeriodoGuarda(100);
        $tipoDossie->setNome('TÉCNICO');
        $tipoDossie->setSigla('TEC');
        $tipoDossie->setDescricao('DOSSIÊ TÉCNICO');
        $tipoDossie->setAtivo(true);

        // Persist entity
        $manager->persist($tipoDossie);

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
        return ['test'];
    }
}
