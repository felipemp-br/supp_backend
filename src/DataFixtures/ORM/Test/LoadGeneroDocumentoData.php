<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadGeneroDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumento;

/**
 * Class LoadGeneroDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGeneroDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $generoDocumento = new GeneroDocumento();
        $generoDocumento->setNome('CARTOGRÁFICO');
        $generoDocumento->setSigla('CART');
        $generoDocumento->setDescricao('CARTOGRÁFICO');

        $manager->persist($generoDocumento);

        $this->addReference(
            'GeneroDocumento-'.$generoDocumento->getNome(),
            $generoDocumento
        );

        $generoDocumento = new GeneroDocumento();
        $generoDocumento->setNome('FILMOGRÁFICO');
        $generoDocumento->setSigla('FILM');
        $generoDocumento->setDescricao('FILMOGRÁFICO');

        $manager->persist($generoDocumento);

        $this->addReference(
            'GeneroDocumento-'.$generoDocumento->getNome(),
            $generoDocumento
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
        return ['test'];
    }
}
