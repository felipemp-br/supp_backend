<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;

/**
 * Class LoadModalidadeAcaoEtiquetaData.
 */
class LoadModalidadeAcaoEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('MINUTA')
            ->setDescricao('GERA AUTOMATICAMENTE UMA MINUTA NA TAREFA ETIQUETADA DE ACORDO COM O MODELO PRÉ-SELECIONADO')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_MINUTA->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('DISTRIBUIÇÃO AUTOMÁTICA')
            ->setDescricao('DISTRIBUIR AS TAREFAS DE FORMA AUTOMÁTICA OU POR RESPONSÁVEL')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_DISTRIBUIR_TAREFA->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('COMPARTILHAMENTO')
            ->setDescricao('COMPARTILHA A TAREFA ENTRE USUÁRIOS')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_COMPARTILHAR_TAREFA->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('OFÍCIO')
            ->setDescricao('GERA AUTOMATICAMENTE UM OFICIO NA TAREFA ETIQUETADA')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_OFICIO->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('DOSSIE')
            ->setDescricao('GERA AUTOMATICAMENTE DOSSIÊS PARA O PROCESSO')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_DOSSIE->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('TAREFA')
            ->setDescricao('GERA AUTOMATICAMENTE TAREFAS PARA O PROCESSO')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_TAREFA->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
        );

        $modalidadeAcaoEtiqueta = (new ModalidadeAcaoEtiqueta())
            ->setValor('LANÇAR ATIVIDADE')
            ->setDescricao('LANÇA UMA ATIVIDADE PARA A TAREFA CORRENTE')
            ->setIdentificador(IdentificadorModalidadeAcaoEtiqueta::TAREFA_LANCAR_ATIVIDADE->value)
            ->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class))
            ->setAtivo(true);

        $manager->persist($modalidadeAcaoEtiqueta);

        $this->addReference(
            'ModalidadeAcaoEtiqueta-'.$modalidadeAcaoEtiqueta->getValor(),
            $modalidadeAcaoEtiqueta
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
        return ['prod', 'dev', 'test'];
    }
}
