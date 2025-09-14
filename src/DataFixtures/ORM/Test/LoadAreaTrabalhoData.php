<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAreaTrabalhoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\AreaTrabalho;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAreaTrabalhoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAreaTrabalhoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{

    public function load(ObjectManager $manager): void
    {
        $areaTrabalho = new AreaTrabalho();
        $areaTrabalho->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $areaTrabalho->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $areaTrabalho->setDono(true);

        // Persist entity
        $manager->persist($areaTrabalho);

        $areaTrabalho = new AreaTrabalho();
        $areaTrabalho->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $areaTrabalho->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));
        $areaTrabalho->setDono(true);

        // Persist entity
        $manager->persist($areaTrabalho);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 5;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['testAreaTrabalho'];
    }
}
