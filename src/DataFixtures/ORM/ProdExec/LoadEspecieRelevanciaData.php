<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEspecieRelevanciaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelevancia;
use SuppCore\AdministrativoBackend\Entity\GeneroRelevancia;

/**
 * Class LoadEspecieRelevanciaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieRelevanciaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieRelevancia = new EspecieRelevancia();
        $especieRelevancia->setNome('PESSOA IDOSA');
        $especieRelevancia->setDescricao('PESSOA IDOSA');
        $especieRelevancia->setGeneroRelevancia(
            $this->getReference('GeneroRelevancia-ADMINISTRATIVO', GeneroRelevancia::class)
        );

        $manager->persist($especieRelevancia);

        $this->addReference(
            'EspecieRelevancia-'.$especieRelevancia->getNome(),
            $especieRelevancia
        );

        $especieRelevancia = new EspecieRelevancia();
        $especieRelevancia->setNome('ALTO VALOR ECONÔMICO');
        $especieRelevancia->setDescricao('ALTO VALOR ECONÔMICO');
        $especieRelevancia->setGeneroRelevancia(
            $this->getReference('GeneroRelevancia-ADMINISTRATIVO', GeneroRelevancia::class)
        );

        $manager->persist($especieRelevancia);

        $this->addReference(
            'EspecieRelevancia-'.$especieRelevancia->getNome(),
            $especieRelevancia
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
        return ['prodexec'];
    }
}
