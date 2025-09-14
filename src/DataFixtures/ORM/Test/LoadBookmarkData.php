<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadBookmarkData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Bookmark;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadBookmarkData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadBookmarkData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $bookmark = new Bookmark();
        $bookmark->setNome('BOOKMARK TESTE');
        $bookmark->setCorHexadecimal('#F0FFFF');
        $bookmark->setDescricao('BOOKMARK TESTE');
        $bookmark->setPagina(1);
        $bookmark->setComponenteDigital(
            $this->getReference('ComponenteDigital-TEMPLATE DESPACHO', ComponenteDigital::class)
        );
        $bookmark->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $bookmark->setJuntada($this->getReference('Juntada-TESTE_11', Juntada::class));
        $bookmark->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($bookmark);

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
