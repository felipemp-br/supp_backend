<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadRepresentanteData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepresentante;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Representante;

/**
 * Class LoadRepresentanteData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRepresentanteData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $representante = new Representante();
        $representante->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $representante->setModalidadeRepresentante(
            $this->getReference('ModalidadeRepresentante-ADVOGADO', ModalidadeRepresentante::class)
        );
        $representante->setInteressado($this->getReference('Interessado-12312312355', Interessado::class));
        $representante->setNome('NOME REPRESENTANTE');
        $representante->setInscricao('SP0000001A');

        // Persist entity
        $manager->persist($representante);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 7;
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
