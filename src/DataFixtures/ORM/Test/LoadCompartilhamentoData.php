<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadCompartilhamentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadCompartilhamentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCompartilhamentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $compartilhamento = new Compartilhamento();
        $compartilhamento->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $compartilhamento->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $compartilhamento->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $compartilhamento->setAssessor(false);

        // Persist entity
        $manager->persist($compartilhamento);

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
