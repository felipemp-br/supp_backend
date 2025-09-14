<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAtividadeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Atividade;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\EspecieAtividade;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\Workflow;

/**
 * Class LoadAtividadeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAtividadeData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $atividade = new Atividade();
        $atividade->setEspecieAtividade(
            $this->getReference('EspecieAtividade-DEMANDAS, ANALISADAS', EspecieAtividade::class)
        );
        $atividade->setObservacao('TESTE_1');
        $atividade->setInformacaoComplementar1(null);

        $atividade->setSetor($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $atividade->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $atividade->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $atividade->setDataHoraConclusao(DateTime::createFromFormat('Y-m-d', '2021-12-05'));
        $atividade->setEncerraTarefa(false);
        $atividade->setWorkflow($this->getReference('Workflow-ELABORAÇÃO DE ATO NORMATIVO', Workflow::class));
        $atividade->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $atividade->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

        // Persist entity
        $manager->persist($atividade);

        // Create reference for later usage
        $this->addReference('Atividade-'.$atividade->getObservacao(), $atividade);

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
