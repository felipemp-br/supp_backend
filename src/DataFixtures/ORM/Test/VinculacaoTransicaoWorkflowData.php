<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/VinculacaoTransicaoWorkflowData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\VinculacaoTransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class VinculacaoTransicaoWorkflowData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoTransicaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoTransicaoWorkflow = new VinculacaoTransicaoWorkflow();
        $vinculacaoTransicaoWorkflow->setWorkflow(
            $this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class)
        );
        $vinculacaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference('TransicaoWorkflow-MINUTA DE ATO NORMATIVO, ELABORADA', TransicaoWorkflow::class)
        );

        // Persist entity
        $manager->persist($vinculacaoTransicaoWorkflow);

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
