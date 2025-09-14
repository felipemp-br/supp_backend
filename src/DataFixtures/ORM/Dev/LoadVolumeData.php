<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadVolumeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;

/**
 * Class LoadVolumeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVolumeData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $volume = new Volume();
        $volume->setNumeracaoSequencial(1);
        $volume->setEncerrado(false);
        $volume->setProcesso($this->getReference('Processo-00100000001202321', Processo::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        // Persist entity
        $manager->persist($volume);

        $volume = new Volume();
        $volume->setNumeracaoSequencial(1);
        $volume->setEncerrado(false);
        $volume->setProcesso($this->getReference('Processo-00100000002202375', Processo::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        // Persist entity
        $manager->persist($volume);

        $volume = new Volume();
        $volume->setNumeracaoSequencial(1);
        $volume->setEncerrado(false);
        $volume->setProcesso($this->getReference('Processo-00100000003202310', Processo::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        // Persist entity
        $manager->persist($volume);

        $volume = new Volume();
        $volume->setNumeracaoSequencial(1);
        $volume->setEncerrado(false);
        $volume->setProcesso($this->getReference('Processo-00100000003202364', Processo::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        // Persist entity
        $manager->persist($volume);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 6;
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
