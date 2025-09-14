<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Relatorio;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;

/**
 * Class LoadRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRelatorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $relatorio = new Relatorio();
        $relatorio->setObservacao('Observação - Teste 1');
        $relatorio->setTipoRelatorio($this->getReference('TipoRelatorio-GERENCIAL', TipoRelatorio::class));
        $manager->persist($relatorio);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
