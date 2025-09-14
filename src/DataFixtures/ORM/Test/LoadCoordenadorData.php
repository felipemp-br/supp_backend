<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadCoordenadorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Coordenador;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadCoordenadorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCoordenadorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-00000000012', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));
        $coordenador->setUnidade(null);
        $coordenador->setOrgaoCentral(null);

        // Persist entity
        $manager->persist($coordenador);

        // Create reference for later usage
        $this->addReference(
            'Coordenador1',
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $coordenador->setSetor(null);
        $coordenador->setUnidade($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $coordenador->setOrgaoCentral(null);

        // Persist entity
        $manager->persist($coordenador);

        // Create reference for later usage
        $this->addReference(
            'Coordenador2',
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-00000000010', Usuario::class));
        $coordenador->setSetor(null);
        $coordenador->setUnidade(null);
        $coordenador->setOrgaoCentral($this->getReference('ModalidadeOrgaoCentral-AGU', ModalidadeOrgaoCentral::class));

        // Persist entity
        $manager->persist($coordenador);

        // Create reference for later usage
        $this->addReference(
            'Coordenador3',
            $coordenador
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
        return ['test'];
    }
}
