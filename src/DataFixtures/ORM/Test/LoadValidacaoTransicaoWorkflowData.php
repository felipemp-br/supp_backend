<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\TipoValidacaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\ValidacaoTransicaoWorkflow;

/**
 * Class LoadValidacaoTransicaoWorkflowData.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadValidacaoTransicaoWorkflowData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $validacaoTransicaoWorkflow = new ValidacaoTransicaoWorkflow();
        $validacaoTransicaoWorkflow->setNome('NOME-1');
        $validacaoTransicaoWorkflow->setDescricao('DESCRIÇÃO-1');
        $validacaoTransicaoWorkflow->setContexto('CONTEXTO-1');
        $validacaoTransicaoWorkflow->setTipoValidacaoWorkflow(
            $this->getReference('TipoAcaoWorkflow-TIPO DE DOCUMENTO', TipoValidacaoWorkflow::class)
        );
        $validacaoTransicaoWorkflow->setTransicaoWorkflow(
            $this->getReference('TransicaoWorkflow-MINUTA DE ATO NORMATIVO, PUBLICADA', TransicaoWorkflow::class)
        );

        // Persist entity
        $manager->persist($validacaoTransicaoWorkflow);

        // Create reference for later usage
        $this->addReference(
            'ValidacaoTransicaoWorkflow-'.$validacaoTransicaoWorkflow->getNome(),
            $validacaoTransicaoWorkflow
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
