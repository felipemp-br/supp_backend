<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoRoleData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\VinculacaoSetorMunicipio;

/**
 * Class LoadVinculacaoSetorMunicipioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoSetorMunicipioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create new entity
        $entity = new VinculacaoSetorMunicipio();
        $entity->setSetor($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));
        $entity->setMunicipio($this->getReference('Municipio-ALEGRE-ES', Municipio::class));

        // Persist entityALEGRE
        $manager->persist($entity);

        $this->addReference('VinculacaoSetorMunicipio-0001', $entity);


        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 4;
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
