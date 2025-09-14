<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVolumeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
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

        $volume->setNumeracaoSequencial(100);
        $volume->setEncerrado(false);

        $volume->setProcesso($this->getReference('Processo-00100000002202022', Processo::class));
        $volume->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $volume->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($volume);

        // Create reference for later usage
        $this->addReference('Volume-'.$volume->getNumeracaoSequencial(), $volume);

        $volume = new Volume();

        $volume->setNumeracaoSequencial(200);
        $volume->setEncerrado(false);

        $volume->setProcesso($this->getReference('Processo-00100000002202022', Processo::class));
        $volume->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $volume->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($volume);

        // Create reference for later usage
        $this->addReference('Volume-'.$volume->getNumeracaoSequencial(), $volume);

        $volume = new Volume();

        $volume->setNumeracaoSequencial(300);
        $volume->setEncerrado(false);

        $volume->setProcesso($this->getReference('Processo-00100000002202022', Processo::class));
        $volume->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $volume->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $volume->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($volume);

        // Create reference for later usage
        $this->addReference('Volume-'.$volume->getNumeracaoSequencial(), $volume);

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
        return ['test'];
    }
}
