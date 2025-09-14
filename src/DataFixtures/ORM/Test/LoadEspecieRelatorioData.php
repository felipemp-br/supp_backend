<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadEspecieRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelatorio;
use SuppCore\AdministrativoBackend\Entity\GeneroRelatorio;

/**
 * Class LoadModalidadeEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieRelatorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especierelatorio = new EspecieRelatorio();
        $especierelatorio->setNome('GERENCIAL');
        $especierelatorio->setAtivo(true);
        $especierelatorio->setDescricao('Gerencial');
        $especierelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-GERENCIAL', GeneroRelatorio::class)
        );
        $manager->persist($especierelatorio);
        $this->addReference('Especie-'.$especierelatorio->getNome(), $especierelatorio);

        $especierelatorio = new EspecieRelatorio();
        $especierelatorio->setNome('ATIVIDADE');
        $especierelatorio->setAtivo(true);
        $especierelatorio->setDescricao('Atividade');
        $especierelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especierelatorio);
        $this->addReference('Especie-'.$especierelatorio->getNome(), $especierelatorio);

        $especierelatorio = new EspecieRelatorio();
        $especierelatorio->setNome('TABELAS');
        $especierelatorio->setAtivo(true);
        $especierelatorio->setDescricao('Tabelas');
        $especierelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-SISTEMA', GeneroRelatorio::class)
        );
        $manager->persist($especierelatorio);
        $this->addReference('Especie-'.$especierelatorio->getNome(), $especierelatorio);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
