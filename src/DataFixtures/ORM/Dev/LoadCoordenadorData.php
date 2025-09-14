<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadCoordenadorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Coordenador;
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
        $coordenador->setUsuario($this->getReference('Usuario-00000000005', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));

        $manager->persist($coordenador);

        $this->addReference(
            'Coordenador-Usuario-00000000005-'.$coordenador->getSetor()->getNome(
            ).'-'.$coordenador->getSetor()->getUnidade()->getSigla(),
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-10000000005', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-SECRETARIA-PGF-SEDE', Setor::class));

        $manager->persist($coordenador);

        $this->addReference(
            'Coordenador-Usuario-10000000005-'.$coordenador->getSetor()->getNome(
            ).'-'.$coordenador->getSetor()->getUnidade()->getSigla(),
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-20000000005', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-SECRETARIA-PGU-SEDE', Setor::class));

        $manager->persist($coordenador);

        $this->addReference(
            'Coordenador-Usuario-20000000005-'.$coordenador->getSetor()->getNome(
            ).'-'.$coordenador->getSetor()->getUnidade()->getSigla(),
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-30000000005', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-SECRETARIA-CGU-SEDE', Setor::class));

        $manager->persist($coordenador);

        $this->addReference(
            'Coordenador-Usuario-30000000005-'.$coordenador->getSetor()->getNome(
            ).'-'.$coordenador->getSetor()->getUnidade()->getSigla(),
            $coordenador
        );

        $coordenador = new Coordenador();
        $coordenador->setUsuario($this->getReference('Usuario-40000000005', Usuario::class));
        $coordenador->setSetor($this->getReference('Setor-SECRETARIA-SGA-SEDE', Setor::class));

        $manager->persist($coordenador);

        $this->addReference(
            'Coordenador-Usuario-40000000005-'.$coordenador->getSetor()->getNome(
            ).'-'.$coordenador->getSetor()->getUnidade()->getSigla(),
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
        return ['dev'];
    }
}
