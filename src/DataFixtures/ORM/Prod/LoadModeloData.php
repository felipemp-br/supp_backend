<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModeloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\Template;

/**
 * Class LoadModeloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModeloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modelo = new Modelo();
        $modelo->setNome('DESPACHO EM BRANCO');
        $modelo->setDescricao('DESPACHO EM BRANCO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-EM BRANCO', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-DESPACHO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO DESPACHO EM BRANCO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-DESPACHO EM BRANCO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('OFÍCIO EM BRANCO');
        $modelo->setDescricao('OFÍCIO EM BRANCO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-EM BRANCO', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-OFÍCIO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO OFÍCIO EM BRANCO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-OFÍCIO EM BRANCO',
            $modelo
        );
        $modelo = new Modelo();
        $modelo->setNome('APROVAÇÃO DE DOCUMENTO');
        $modelo->setDescricao('DESPACHO EM BRANCO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-EM BRANCO', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-DESPACHO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO DESPACHO DE APROVAÇÃO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-APROVAÇÃO DE DOCUMENTO',
            $modelo
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
        return ['prod', 'dev', 'test'];
    }
}
