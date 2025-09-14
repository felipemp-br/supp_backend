<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

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
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadTransicaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade($this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, ELABORADA', EspecieAtividade::class));
        $transicaoWorkflow->setEspecieTarefaFrom($this->getReference('EspecieTarefa-PARTICIPAR DE REUNIÃO', EspecieTarefa::class));
        $transicaoWorkflow->setEspecieTarefaTo($this->getReference('EspecieTarefa-ELABORAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL', Workflow::class));

        $manager->persist($transicaoWorkflow);

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade($this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO', EspecieAtividade::class));
        $transicaoWorkflow->setEspecieTarefaFrom($this->getReference('EspecieTarefa-REVISAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));
        $transicaoWorkflow->setEspecieTarefaTo($this->getReference('EspecieTarefa-ASSINAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL', Workflow::class));

        $manager->persist($transicaoWorkflow);

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade($this->getReference('EspecieAtividade-MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO', EspecieAtividade::class));
        $transicaoWorkflow->setEspecieTarefaFrom($this->getReference('EspecieTarefa-REVISAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));
        $transicaoWorkflow->setEspecieTarefaTo($this->getReference('EspecieTarefa-ELABORAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL', Workflow::class));

        $manager->persist($transicaoWorkflow);

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade($this->getReference('EspecieAtividade-PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS', EspecieAtividade::class));
        $transicaoWorkflow->setEspecieTarefaFrom($this->getReference('EspecieTarefa-PARTICIPAR DE REUNIÃO', EspecieTarefa::class));
        $transicaoWorkflow->setEspecieTarefaTo($this->getReference('EspecieTarefa-APROVAR DOCUMENTO', EspecieTarefa::class));
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL', Workflow::class));

        $manager->persist($transicaoWorkflow);

        $transicaoWorkflow = new TransicaoWorkflow();
        $transicaoWorkflow->setEspecieAtividade($this->getReference('EspecieAtividade-PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS', EspecieAtividade::class));
        $transicaoWorkflow->setEspecieTarefaFrom($this->getReference('EspecieTarefa-APROVAR DOCUMENTO', EspecieTarefa::class));
        $transicaoWorkflow->setEspecieTarefaTo($this->getReference('EspecieTarefa-APROVAR DOCUMENTO', EspecieTarefa::class));
        $transicaoWorkflow->setWorkflow($this->getReference('Workflow-PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL', Workflow::class));

        $manager->persist($transicaoWorkflow);

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
        return 4;
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
