<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadAssuntoAdministrativoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;

/**
 * Class LoadAssuntoAdministrativoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAssuntoAdministrativoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ATIVIDADE MEIO');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('RECURSOS HUMANOS');
        $assuntoAdministrativo->setParent(
            $this->getReference('AssuntoAdministrativo-ATIVIDADE MEIO', AssuntoAdministrativo::class)
        );

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('ATIVIDADE FIM');

        $manager->persist($assuntoAdministrativo);

        $this->addReference('AssuntoAdministrativo-'.$assuntoAdministrativo->getNome(), $assuntoAdministrativo);

        $assuntoAdministrativo = new AssuntoAdministrativo();
        $assuntoAdministrativo->setNome('DIREITO TRIBUTÁRIO');
        $assuntoAdministrativo->setParent(
            $this->getReference('AssuntoAdministrativo-ATIVIDADE FIM', AssuntoAdministrativo::class)
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
        return ['dev'];
    }
}
