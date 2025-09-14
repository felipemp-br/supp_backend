<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadTransicaoWorkflowData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieAtividade;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadTransicaoWorkflowData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTransicaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade(
            $this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, ELABORADA', EspecieAtividade::class)
        );
        $transicaoWorkflow->setEspecieTarefaFrom(
            $this->getReference('EspecieTarefa-ELABORAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setEspecieTarefaTo(
            $this->getReference('EspecieTarefa-REVISAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setWorkflow(
            $this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class)
        );
        $transicaoWorkflow->setQtdDiasPrazo(5);

        // Persist entity
        $manager->persist($transicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'TransicaoWorkflow-'.$transicaoWorkflow->getEspecieAtividade()->getNome(),
            $transicaoWorkflow
        )
        ;

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade(
            $this->getReference(
                'EspecieAtividade-MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO',
                EspecieAtividade::class
            )
        );
        $transicaoWorkflow->setEspecieTarefaFrom(
            $this->getReference('EspecieTarefa-REVISAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setEspecieTarefaTo(
            $this->getReference('EspecieTarefa-ASSINAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class));
        $transicaoWorkflow->setQtdDiasPrazo(5);

        // Persist entity
        $manager->persist($transicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'TransicaoWorkflow-'.$transicaoWorkflow->getEspecieAtividade()->getNome(),
            $transicaoWorkflow
        );

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade(
            $this->getReference(
                'EspecieAtividade-MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO',
                EspecieAtividade::class
            )
        );
        $transicaoWorkflow->setEspecieTarefaFrom(
            $this->getReference('EspecieTarefa-REVISAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setEspecieTarefaTo(
            $this->getReference('EspecieTarefa-ELABORAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setWorkflow(
            $this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class)
        );
        $transicaoWorkflow->setQtdDiasPrazo(5);

        // Persist entity
        $manager->persist($transicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'TransicaoWorkflow-'.$transicaoWorkflow->getEspecieAtividade()->getNome(),
            $transicaoWorkflow
        );

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade(
            $this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, ASSINADA', EspecieAtividade::class)
        );
        $transicaoWorkflow->setEspecieTarefaFrom(
            $this->getReference('EspecieTarefa-ASSINAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setEspecieTarefaTo(
            $this->getReference('EspecieTarefa-PUBLICAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setWorkflow(
            $this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class)
        );
        $transicaoWorkflow->setQtdDiasPrazo(5);

        // Persist entity
        $manager->persist($transicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'TransicaoWorkflow-'.$transicaoWorkflow->getEspecieAtividade()->getNome(),
            $transicaoWorkflow
        );

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade(
            $this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, PUBLICADA', EspecieAtividade::class)
        );
        $transicaoWorkflow->setEspecieTarefaFrom(
            $this->getReference('EspecieTarefa-PUBLICAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class)
        );
        $transicaoWorkflow->setEspecieTarefaTo(
            $this->getReference('EspecieTarefa-MANTER SOB GUARDA NO ARQUIVO CORRENTE', EspecieTarefa::class)
        );
        $transicaoWorkflow->setWorkflow(
            $this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class)
        );
        $transicaoWorkflow->setQtdDiasPrazo(5);

        // Persist entity
        $manager->persist($transicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'TransicaoWorkflow-'.$transicaoWorkflow->getEspecieAtividade()->getNome(),
            $transicaoWorkflow
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
        return ['test'];
    }
}
