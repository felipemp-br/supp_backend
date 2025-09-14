<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAcaoTransicaoWorkflowData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\AcaoTransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\TipoAcaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadAcaoTransicaoWorkflowData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAcaoTransicaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $acaoTransicaoWorkflow = new AcaoTransicaoWorkflow();
        $acaoTransicaoWorkflow->setContexto('Ação Transição Workflow 01');
        $acaoTransicaoWorkflow->setTipoAcaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-MINUTA', TipoAcaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference('TransicaoWorkflow-MINUTA DE ATO NORMATIVO, ELABORADA', TransicaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($acaoTransicaoWorkflow);

        $acaoTransicaoWorkflow = new AcaoTransicaoWorkflow();
        $acaoTransicaoWorkflow->setContexto('Ação Transição Workflow 02');
        $acaoTransicaoWorkflow->setTipoAcaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-MINUTA', TipoAcaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference(
                'TransicaoWorkflow-MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO',
                TransicaoWorkflow::class
            )
        );
        $acaoTransicaoWorkflow->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($acaoTransicaoWorkflow);

        $acaoTransicaoWorkflow = new AcaoTransicaoWorkflow();
        $acaoTransicaoWorkflow->setContexto('Ação Transição Workflow 03');
        $acaoTransicaoWorkflow->setTipoAcaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-MINUTA', TipoAcaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference(
                'TransicaoWorkflow-MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO',
                TransicaoWorkflow::class
            )
        );
        $acaoTransicaoWorkflow->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($acaoTransicaoWorkflow);

        $acaoTransicaoWorkflow = new AcaoTransicaoWorkflow();
        $acaoTransicaoWorkflow->setContexto('Ação Transição Workflow 04');
        $acaoTransicaoWorkflow->setTipoAcaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-COMPARTILHAMENTO', TipoAcaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference('TransicaoWorkflow-MINUTA DE ATO NORMATIVO, PUBLICADA', TransicaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($acaoTransicaoWorkflow);

        $acaoTransicaoWorkflow = new AcaoTransicaoWorkflow();
        $acaoTransicaoWorkflow->setContexto('Ação Transição Workflow 05');
        $acaoTransicaoWorkflow->setTipoAcaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-OFÍCIO', TipoAcaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference('TransicaoWorkflow-MINUTA DE ATO NORMATIVO, PUBLICADA', TransicaoWorkflow::class)
        );
        $acaoTransicaoWorkflow->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($acaoTransicaoWorkflow);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
