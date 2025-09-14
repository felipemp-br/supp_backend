<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadWorkflowData.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $workflow = (new Workflow())
            ->setNome($this->getReference('EspecieProcesso-PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL', EspecieProcesso::class)->getNome())
            ->setDescricao($this->getReference('EspecieProcesso-PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL', EspecieProcesso::class)->getNome())
            ->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class))
            ->setEspecieTarefaInicial($this->getReference('EspecieTarefa-ELABORAR MINUTA DE ATO NORMATIVO', EspecieTarefa::class));

        $manager->persist($workflow);

        $this->addReference(
            'Workflow-'.$workflow->getNome(),
            $workflow
        );
        $workflow = (new Workflow())
            ->setNome($this->getReference('EspecieProcesso-ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL', EspecieProcesso::class)->getNome())
            ->setDescricao($this->getReference('EspecieProcesso-ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL', EspecieProcesso::class)->getNome())
            ->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class))
            ->setEspecieTarefaInicial($this->getReference('EspecieTarefa-ANALISAR DEMANDAS', EspecieTarefa::class));

        $manager->persist($workflow);

        $this->addReference(
            'Workflow-'.$workflow->getNome(),
            $workflow
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
        return 3;
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
