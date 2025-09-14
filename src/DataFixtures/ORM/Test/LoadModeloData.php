<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;
use SuppCore\AdministrativoBackend\Entity\Template;

/**
 * Class LoadModeloData.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadModeloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modelo = new ModeloEntity();
        $modelo->setDescricao('DESCRICAO TESTE');
        $modelo->setTemplate($this->getReference('Template-DESPACHO', Template::class));
        $modelo->setNome('TESTE_FIXTURE_1');
        $modelo->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-INDIVIDUAL', ModalidadeModelo::class));

        $manager->persist($modelo);
        if (!$this->hasReference('Modelo-'.$modelo->getNome(), Modelo::class)) {
            $this->addReference('Modelo-'.$modelo->getNome(), $modelo);
        }

        $modelo = new ModeloEntity();
        $modelo->setDescricao('DESCRICAO TESTE2');
        $modelo->setNome('TESTE_FIXTURE_2');
        $modelo->setTemplate($this->getReference('Template-OFÍCIO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO OFÍCIO EM BRANCO2', Documento::class));
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-INDIVIDUAL', ModalidadeModelo::class));

        $manager->persist($modelo);

        if (!$this->hasReference('Modelo-'.$modelo->getNome(), Modelo::class)) {
            $this->addReference('Modelo-'.$modelo->getNome(), $modelo);
        }

        $modelo = new ModeloEntity();
        $modelo->setDescricao('DESCRICAO TESTE3');
        $modelo->setNome('TESTE_FIXTURE_3');
        $modelo->setTemplate($this->getReference('Template-OFÍCIO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO DESPACHO DE APROVAÇÃO2', Documento::class));
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-INDIVIDUAL', ModalidadeModelo::class));

        $manager->persist($modelo);

        if (!$this->hasReference('Modelo-'.$modelo->getNome(), Modelo::class)) {
            $this->addReference('Modelo-'.$modelo->getNome(), $modelo);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['testModelo'];
    }
}
