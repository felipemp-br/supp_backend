<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadTemplateData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

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
        $template->setNome('DESPACHO');
        $template->setDescricao('DESPACHO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate(
            $this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class)
        );
        $template->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));

        $manager->persist($template);

        $this->addReference(
            'Template-DESPACHO',
            $template
        );

        $template = new Template();
        $template->setNome('OFÍCIO');
        $template->setDescricao('OFÍCIO');
        $template->setAtivo(true);
        $template->setModalidadeTemplate(
            $this->getReference('ModalidadeTemplate-ADMINISTRATIVO', ModalidadeTemplate::class)
        );
        $template->setDocumento($this->getReference('Documento-TEMPLATE OFÍCIO', Documento::class));

        $manager->persist($template);

        $this->addReference(
            'Template-OFÍCIO',
            $template
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
        return ['prod', 'dev', 'test'];
    }
}
