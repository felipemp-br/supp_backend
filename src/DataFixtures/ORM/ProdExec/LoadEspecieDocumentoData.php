<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEspecieDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumento;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumento;

/**
 * Class LoadEspecieDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieDocumento = new EspecieDocumento();
        $especieDocumento->setNome('ADMINISTRATIVO');
        $especieDocumento->setSigla('ADM');
        $especieDocumento->setDescricao('ADMINISTRATIVO');
        $especieDocumento->setGeneroDocumento(
            $this->getReference('GeneroDocumento-DOCUMENTAL', GeneroDocumento::class)
        );

        $manager->persist($especieDocumento);

        $this->addReference(
            'EspecieDocumento-'.$especieDocumento->getNome(),
            $especieDocumento
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
