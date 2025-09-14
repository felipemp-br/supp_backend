<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadNumeroUnicoDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\NumeroUnicoDocumento;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;

/**
 * Class LoadNumeroUnicoDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadNumeroUnicoDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $numeroUnicoDocumento = new NumeroUnicoDocumento();
        $numeroUnicoDocumento->setSetor($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));
        $numeroUnicoDocumento->setTipoDocumento($this->getReference('TipoDocumento-ALVARÁ', TipoDocumento::class));
        $numeroUnicoDocumento->setAno(2021);
        $numeroUnicoDocumento->setSequencia(1);

        // Persist entity
        $manager->persist($numeroUnicoDocumento);

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
