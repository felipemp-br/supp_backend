<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeDocumentoIdentificadorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDocumentoIdentificador;

/**
 * Class LoadModalidadeDocumentoIdentificadorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeDocumentoIdentificadorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CARTEIRA DE IDENTIDADE');
        $modalidadeDocumentoIdentificador->setDescricao('CARTEIRA DE IDENTIDADE');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CARTEIRA NACIONAL DE HABILITAÇÃO');
        $modalidadeDocumentoIdentificador->setDescricao('CARTEIRA NACIONAL DE HABILITAÇÃO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('TÍTULO DE ELEITOR');
        $modalidadeDocumentoIdentificador->setDescricao('TÍTULO DE ELEITOR');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CERTIDÃO DE NASCIMENTO');
        $modalidadeDocumentoIdentificador->setDescricao('CERTIDÃO DE NASCIMENTO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CERTIDÃO DE CASAMENTO');
        $modalidadeDocumentoIdentificador->setDescricao('CERTIDÃO DE CASAMENTO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CARTEIRA DE TRABALHO');
        $modalidadeDocumentoIdentificador->setDescricao('CARTEIRA DE TRABALHO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CADASTRO NO MINISTÉRIO DA FAZENDA BRASILEIRO');
        $modalidadeDocumentoIdentificador->setDescricao('CADASTRO NO MINISTÉRIO DA FAZENDA BRASILEIRO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CADASTRO ESPECÍFICO DO INSS');
        $modalidadeDocumentoIdentificador->setDescricao('CADASTRO ESPECÍFICO DO INSS');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('NÚMERO DE IDENTIFICAÇÃO DO TRABALHO');
        $modalidadeDocumentoIdentificador->setDescricao('NÚMERO DE IDENTIFICAÇÃO DO TRABALHO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('CADASTRO EM CONSELHOS PROFISSIONAIS');
        $modalidadeDocumentoIdentificador->setDescricao('CADASTRO EM CONSELHOS PROFISSIONAIS');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('IDENTIDADE FUNCIONAL');
        $modalidadeDocumentoIdentificador->setDescricao('IDENTIDADE FUNCIONAL');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('NÚMERO DE CADASTRO NA ORDEM DOS ADVOGADOS DO BRASIL');
        $modalidadeDocumentoIdentificador->setDescricao('NÚMERO DE CADASTRO NA ORDEM DOS ADVOGADOS DO BRASIL');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('NÚMERO DE INSCRIÇÃO EMPRESARIAL');
        $modalidadeDocumentoIdentificador->setDescricao('NÚMERO DE INSCRIÇÃO EMPRESARIAL');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('REGISTRO DE IDENTIFICAÇÃO DO ESTRANGEIRO');
        $modalidadeDocumentoIdentificador->setDescricao('REGISTRO DE IDENTIFICAÇÃO DO ESTRANGEIRO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('NÚMERO NO PROGRAMA DE INTEGRAÇÃO SOCIAL');
        $modalidadeDocumentoIdentificador->setDescricao('NÚMERO NO PROGRAMA DE INTEGRAÇÃO SOCIAL');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('PASSAPORTE');
        $modalidadeDocumentoIdentificador->setDescricao('PASSAPORTE');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('REGISTRO INDIVIDUAL DO CIDADÃO');
        $modalidadeDocumentoIdentificador->setDescricao('REGISTRO INDIVIDUAL DO CIDADÃO');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
        );

        // --- //

        $modalidadeDocumentoIdentificador = new ModalidadeDocumentoIdentificador();
        $modalidadeDocumentoIdentificador->setValor('MATRÍCULA SIAPE');
        $modalidadeDocumentoIdentificador->setDescricao('MATRÍCULA SIAPE');

        $manager->persist($modalidadeDocumentoIdentificador);

        $this->addReference(
            'ModalidadeDocumentoIdentificador-'.$modalidadeDocumentoIdentificador->getValor(),
            $modalidadeDocumentoIdentificador
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
        return 1;
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
