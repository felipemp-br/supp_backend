<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadGeneroTarefaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa;

/**
 * Class LoadGeneroTarefaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGeneroTarefaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $generoTarefa = new GeneroTarefa();
        $generoTarefa->setNome('ADMINISTRATIVO');
        $generoTarefa->setDescricao('ADMINISTRATIVO');

        $manager->persist($generoTarefa);

        $this->addReference(
            'GeneroTarefa-'.$generoTarefa->getNome(),
            $generoTarefa
        );

        $generoTarefa = new GeneroTarefa();
        $generoTarefa->setNome('ARQUIVÍSTICO');
        $generoTarefa->setDescricao('ARQUIVÍSTICO');

        $manager->persist($generoTarefa);

        $this->addReference(
            'GeneroTarefa-'.$generoTarefa->getNome(),
            $generoTarefa
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
        return ['prodexec'];
    }
}
