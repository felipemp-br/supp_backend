<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadColaboradorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Estado;
use SuppCore\AdministrativoBackend\Entity\Feriado;
use SuppCore\AdministrativoBackend\Entity\Municipio;

/**
 * Class LoadCFeriadoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadFeriadoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $date = new DateTime('2021-01-01');

        $feriado = new Feriado();
        $feriado->setNome('Feriado 01');
        $feriado->setDataFeriado($date);
        $feriado->setAtivo(true);
        $feriado->setEstado($this->getReference('Estado-SP', Estado::class));
        $feriado->setMunicipio($this->getReference('Municipio-OSASCO-SP', Municipio::class));
        $manager->persist($feriado);

        $this->addReference('Feriado-001', $feriado);

        $feriado = new Feriado();
        $feriado->setNome('Feriado 02');
        $feriado->setDataFeriado($date);
        $feriado->setAtivo(true);
        $feriado->setEstado($this->getReference('Estado-SP', Estado::class));
        $feriado->setMunicipio($this->getReference('Municipio-CAMPINAS-SP', Municipio::class));
        $manager->persist($feriado);

        $this->addReference('Feriado-002', $feriado);

        $feriado = new Feriado();
        $feriado->setNome('Feriado 03');
        $feriado->setDataFeriado($date);
        $feriado->setAtivo(true);
        $feriado->setEstado($this->getReference('Estado-SP', Estado::class));
        $feriado->setMunicipio($this->getReference('Municipio-LORENA-SP', Municipio::class));
        $manager->persist($feriado);

        $this->addReference('Feriado-003', $feriado);

        $feriado = new Feriado();
        $feriado->setNome('Feriado 04');
        $feriado->setDataFeriado($date);
        $feriado->setAtivo(true);
        $feriado->setEstado($this->getReference('Estado-SP', Estado::class));
        $feriado->setMunicipio($this->getReference('Municipio-CRUZEIRO-SP', Municipio::class));
        $manager->persist($feriado);

        $this->addReference('Feriado-004', $feriado);

        $feriado = new Feriado();
        $feriado->setNome('Feriado 05');
        $feriado->setDataFeriado($date);
        $feriado->setAtivo(true);
        $feriado->setEstado($this->getReference('Estado-SP', Estado::class));
        $feriado->setMunicipio($this->getReference('Municipio-PIRACICABA-SP', Municipio::class));
        $manager->persist($feriado);

        $this->addReference('Feriado-005', $feriado);

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
        return ['test'];
    }
}
