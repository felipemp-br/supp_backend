<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadContatoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Contato;
use SuppCore\AdministrativoBackend\Entity\GrupoContato;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\TipoContato;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadContatoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadContatoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $contato = new Contato();
        $contato->setTipoContato($this->getReference('TipoContato-USUÁRIO', TipoContato::class));
        $contato->setGrupoContato($this->getReference('GrupoContato-USUÁRIO', GrupoContato::class));
        $contato->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $contato->setSetor($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));

        // Persist entity
        $manager->persist($contato);

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
