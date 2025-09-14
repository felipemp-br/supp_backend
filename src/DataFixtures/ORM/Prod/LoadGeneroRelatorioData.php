<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadGeneroRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GeneroRelatorio;

/**
 * Class LoadGeneroRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGeneroRelatorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Adicionar novos gêneros no final da listagem

        $generoRelatorio = new GeneroRelatorio();
        $generoRelatorio->setNome('OPERACIONAL');
        $generoRelatorio->setDescricao('OPERACIONAL');

        $manager->persist($generoRelatorio);

        $this->addReference(
            'GeneroRelatorio-'.$generoRelatorio->getNome(),
            $generoRelatorio
        );

        $generoRelatorio = new GeneroRelatorio();
        $generoRelatorio->setNome('GERENCIAL');
        $generoRelatorio->setDescricao('GERENCIAL');

        $manager->persist($generoRelatorio);

        $this->addReference(
            'GeneroRelatorio-'.$generoRelatorio->getNome(),
            $generoRelatorio
        );

        $generoRelatorio = new GeneroRelatorio();
        $generoRelatorio->setNome('SISTEMA');
        $generoRelatorio->setDescricao('SISTEMA');

        $manager->persist($generoRelatorio);

        $this->addReference(
            'GeneroRelatorio-'.$generoRelatorio->getNome(),
            $generoRelatorio
        );

        $generoRelatorio = new GeneroRelatorio();
        $generoRelatorio->setNome('ARQUIVÍSTICO');
        $generoRelatorio->setDescricao('ARQUIVÍSTICO');

        $manager->persist($generoRelatorio);

        $this->addReference(
            'GeneroRelatorio-'.$generoRelatorio->getNome(),
            $generoRelatorio
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
        return 1;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prod', 'dev', 'test'];
    }
}
