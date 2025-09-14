<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEspecieProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso;

/**
 * Class LoadEspecieProcessoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieProcessoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUM');
        $especieProcesso->setDescricao('COMUM');
        $especieProcesso->setGeneroProcesso(
            $this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class)
        );

        $manager->persist($especieProcesso);

        $this->addReference(
            'EspecieProcesso-'.$especieProcesso->getNome(),
            $especieProcesso
        );

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ELABORAÇÃO DE ATO NORMATIVO');
        $especieProcesso->setDescricao('ELABORAÇÃO DE ATO NORMATIVO');
        $especieProcesso->setGeneroProcesso(
            $this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class)
        );

        $manager->persist($especieProcesso);

        $this->addReference(
            'EspecieProcesso-'.$especieProcesso->getNome(),
            $especieProcesso
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
        return 2;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
