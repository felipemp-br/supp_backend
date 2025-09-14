<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadTemplateData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTemplate;
use SuppCore\AdministrativoBackend\Entity\Template;

/**
 * Class LoadTemplateData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTemplateData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setNome('EXPOSICAO_DE_MOTIVOS');
        $template->setDescricao('EXPOSIÇÃO DE MOTIVOS');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_EXPOSICAO_MOTIVOS', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-EXPOSICAO_DE_MOTIVOS', $template);

        $template = new Template();
        $template->setNome('MEMORANDO');
        $template->setDescricao('MEMORANDO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_MEMORANDO', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-MEMORANDO', $template);

        $template = new Template();
        $template->setNome('MENSAGEM');
        $template->setDescricao('MENSAGEM');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_MENSAGEM', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-MENSAGEM', $template);

        $template = new Template();
        $template->setNome('NOTA_TECNICA');
        $template->setDescricao('NOTA TÉCNICA');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_NOTA_TECNICA', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-NOTA_TECNICA', $template);

        $template = new Template();
        $template->setNome('OFICIO');
        $template->setDescricao('OFÍCIO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_OFICIO', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-OFICIO', $template);

        $template = new Template();
        $template->setNome('DESPACHO');
        $template->setDescricao('DESPACHO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_DESPACHO', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-DESPACHO', $template);

        $template = new Template();
        $template->setNome('DESPACHO_NUMERADO');
        $template->setDescricao('DESPACHO COM NUMERAÇÃO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_DESPACHO_NUMERADO', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-DESPACHO_NUMERADO', $template);

        $template = new Template();
        $template->setNome('PORTARIA');
        $template->setDescricao('PORTARIA');
        $template->setAtivo(true);
        $template->setModalidadeTemplate($this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class));
        $template->setDocumento($this->getReference('Documento-TEMPLATE_PORTARIA', Documento::class));

        $manager->persist($template);

        $this->addReference('Template-PORTARIA', $template);

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
        return ['prodexec'];
    }
}
