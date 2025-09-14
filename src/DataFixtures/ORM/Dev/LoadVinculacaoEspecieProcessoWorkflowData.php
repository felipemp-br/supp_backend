<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEspecieProcessoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadVinculacaoEspecieProcessoWorkflowData.
 *
 */
class LoadVinculacaoEspecieProcessoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoEspecieProcessoWorkflow = (new VinculacaoEspecieProcessoWorkflow())
            ->setWorkflow($this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class))
            ->setEspecieProcesso(
                $this->getReference('EspecieProcesso-ELABORAÇÃO DE ATO NORMATIVO', EspecieProcesso::class)
            );

        $manager->persist($vinculacaoEspecieProcessoWorkflow);

        $this->addReference(
            'VinculacaoEspecieProcessoWorkflow-ELABORAÇÃO DE ATO NORMATIVO-ELABORAÇÃO DE ATO NORMATIVO',
            $vinculacaoEspecieProcessoWorkflow
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
        return ['dev', 'test'];
    }
}
