<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoUsuarioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;

/**
 * Class LoadVinculacaoUsuarioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoUsuarioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoUsuario = new VinculacaoUsuario();
        $vinculacaoUsuario->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $vinculacaoUsuario->setUsuarioVinculado($this->getReference('Usuario-00000000005', Usuario::class));
        $vinculacaoUsuario->setEncerraTarefa(true);
        $vinculacaoUsuario->setCompartilhaTarefa(true);
        $vinculacaoUsuario->setCriaMinuta(true);
        $vinculacaoUsuario->setCriaOficio(true);

        // Persist entity
        $manager->persist($vinculacaoUsuario);

        $vinculacaoUsuario = new VinculacaoUsuario();
        $vinculacaoUsuario->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $vinculacaoUsuario->setUsuarioVinculado($this->getReference('Usuario-00000000009', Usuario::class));
        $vinculacaoUsuario->setEncerraTarefa(true);
        $vinculacaoUsuario->setCompartilhaTarefa(true);
        $vinculacaoUsuario->setCriaMinuta(true);
        $vinculacaoUsuario->setCriaOficio(true);

        // Persist entity
        $manager->persist($vinculacaoUsuario);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
