<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModeloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

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
        $modelo->setDocumento($this->getReference('Documento-MODELO_DESPACHO_EM_BRANCO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-DESPACHO EM BRANCO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('OFÍCIO EM BRANCO');
        $modelo->setDescricao('OFÍCIO EM BRANCO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-EM BRANCO', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-OFICIO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_OFICIO_EM_BRANCO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-OFÍCIO EM BRANCO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO OFÍCIO');
        $modelo->setDescricao('MODELO DE OFÍCIO COM CABEÇALHO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-OFICIO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_OFICIO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO OFÍCIO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO DESPACHO');
        $modelo->setDescricao('MODELO DE DESPACHO SEM NUMERAÇÃO COM CABEÇALHO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-DESPACHO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_DESPACHO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO DESPACHO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO DESPACHO NUMERADO');
        $modelo->setDescricao('MODELO DE DESPACHO COM NUMERAÇÃO COM CABEÇALHO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-DESPACHO_NUMERADO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_DESPACHO_NUMERADO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO DESPACHO NUMERADO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO EXPOSIÇÃO DE MOTIVOS');
        $modelo->setDescricao('MODELO DE EXPOSIÇÃO DE MOTIVOS');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-EXPOSICAO_DE_MOTIVOS', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_EXPOSICAO_MOTIVOS', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO EXPOSIÇÃO DE MOTIVOS',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO MEMORANDO');
        $modelo->setDescricao('MODELO DE MEMORANDO');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-MEMORANDO', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_MEMORANDO', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO MEMORANDO',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO MENSAGEM');
        $modelo->setDescricao('MODELO DE MENSAGEM');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-MENSAGEM', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_MENSAGEM', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO MENSAGEM',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO NOTA TÉCNICA');
        $modelo->setDescricao('MODELO DE NOTA TÉCNICA');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-NOTA_TECNICA', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_NOTA_TECNICA', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO NOTA TÉCNICA',
            $modelo
        );

        $modelo = new Modelo();
        $modelo->setNome('MODELO PORTARIA');
        $modelo->setDescricao('MODELO DE PORTARIA');
        $modelo->setModalidadeModelo($this->getReference('ModalidadeModelo-NACIONAL', ModalidadeModelo::class));
        $modelo->setTemplate($this->getReference('Template-PORTARIA', Template::class));
        $modelo->setDocumento($this->getReference('Documento-MODELO_PORTARIA', Documento::class));

        $manager->persist($modelo);

        $this->addReference(
            'Modelo-MODELO PORTARIA',
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
        return ['prodexec'];
    }
}
