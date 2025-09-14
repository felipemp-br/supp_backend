<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoWorkflowData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\VinculacaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadVinculacaoWorkflowData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoWorkflow = new VinculacaoWorkflow();
        $vinculacaoWorkflow->setTarefaInicial($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $vinculacaoWorkflow->setTarefaAtual($this->getReference('Tarefa-TESTE_2', Tarefa::class));
        $vinculacaoWorkflow->setWorkflow($this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class));

        // Persist entity
        $manager->persist($vinculacaoWorkflow);

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
        return 6;
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
