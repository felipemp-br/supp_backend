<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEspecieSetorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\GeneroSetor;

/**
 * Class LoadEspecieSetorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieSetorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('PROTOCOLO');
        $especieSetor->setDescricao('PROTOCOLO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('ARQUIVO');
        $especieSetor->setDescricao('ARQUIVO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('SECRETARIA');
        $especieSetor->setDescricao('SECRETARIA');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('DEPARTAMENTO OU SUBSECRETARIA');
        $especieSetor->setDescricao('DEPARTAMENTO OU SUBSECRETARIA');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('COORDENAÇÃO-GERAL');
        $especieSetor->setDescricao('COORDENAÇÃO-GERAL');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('COORDENAÇÃO');
        $especieSetor->setDescricao('COORDENAÇÃO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('DIVISÃO');
        $especieSetor->setDescricao('DIVISÃO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('SERVIÇO');
        $especieSetor->setDescricao('SERVIÇO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('GABINETE');
        $especieSetor->setDescricao('GABINETE');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('SEÇÃO');
        $especieSetor->setDescricao('SEÇÃO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('SETOR');
        $especieSetor->setDescricao('SETOR');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
        );

        $especieSetor = new EspecieSetor();
        $especieSetor->setNome('NÚCLEO');
        $especieSetor->setDescricao('NÚCLEO');
        $especieSetor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $manager->persist($especieSetor);

        $this->addReference(
            'EspecieSetor-'.$especieSetor->getNome(),
            $especieSetor
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
        return 2;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prodexec'];
    }
}
