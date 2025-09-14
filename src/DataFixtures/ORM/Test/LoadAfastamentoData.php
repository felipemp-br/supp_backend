<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadModalidadeEtiquetaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Afastamento;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAfastamento;

/**
 * Class LoadModalidadeEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAfastamentoData extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $afastamento = new Afastamento();
        $afastamento->setModalidadeAfastamento(
            $this->getReference('ModalidadeAfastamento-RECESSO', ModalidadeAfastamento::class)
        );
        $afastamento->setColaborador($this->getReference('Colaborador-00000000002', Colaborador::class));
        $afastamento->setDataInicio(DateTime::createFromFormat('Y-m-d', '2020-01-05'));
        $afastamento->setDataInicioBloqueio(DateTime::createFromFormat('Y-m-d', '2020-01-01'));
        $afastamento->setDataFim(DateTime::createFromFormat('Y-m-d', '2020-02-05'));
        $afastamento->setDataFimBloqueio(DateTime::createFromFormat('Y-m-d', '2020-02-01'));

        $manager->persist($afastamento);

        $afastamento = new Afastamento();
        $afastamento->setModalidadeAfastamento(
            $this->getReference('ModalidadeAfastamento-LICENÇA', ModalidadeAfastamento::class)
        );
        $afastamento->setColaborador($this->getReference('Colaborador-00000000003', Colaborador::class));
        $afastamento->setDataInicio(DateTime::createFromFormat('Y-m-d', '2020-01-05'));
        $afastamento->setDataInicioBloqueio(DateTime::createFromFormat('Y-m-d', '2020-01-01'));
        $afastamento->setDataFim(DateTime::createFromFormat('Y-m-d', '2020-02-05'));
        $afastamento->setDataFimBloqueio(DateTime::createFromFormat('Y-m-d', '2020-02-01'));
        $manager->persist($afastamento);

        $afastamento = new Afastamento();
        $afastamento->setModalidadeAfastamento(
            $this->getReference('ModalidadeAfastamento-FÉRIAS', ModalidadeAfastamento::class)
        );
        $afastamento->setColaborador($this->getReference('Colaborador-00000000004', Colaborador::class));
        $afastamento->setDataInicio(DateTime::createFromFormat('Y-m-d', '2020-01-05'));
        $afastamento->setDataInicioBloqueio(DateTime::createFromFormat('Y-m-d', '2020-01-01'));
        $afastamento->setDataFim(DateTime::createFromFormat('Y-m-d', '2020-02-05'));
        $afastamento->setDataFimBloqueio(DateTime::createFromFormat('Y-m-d', '2020-02-01'));
        $manager->persist($afastamento);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 4;
    }
}
