<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeVinculacaoDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoDocumento;

/**
 * Class LoadModalidadeVinculacaoDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeVinculacaoDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeVinculacaoDocumento = new ModalidadeVinculacaoDocumento();
        $modalidadeVinculacaoDocumento->setValor('ANEXO');
        $modalidadeVinculacaoDocumento->setDescricao('ANEXO');

        $manager->persist($modalidadeVinculacaoDocumento);

        $this->addReference('ModalidadeVinculacaoDocumento-'.$modalidadeVinculacaoDocumento->getValor(), $modalidadeVinculacaoDocumento);

        $modalidadeVinculacaoDocumento = new ModalidadeVinculacaoDocumento();
        $modalidadeVinculacaoDocumento->setValor('APROVAÇÃO');
        $modalidadeVinculacaoDocumento->setDescricao('APROVAÇÃO');

        $manager->persist($modalidadeVinculacaoDocumento);

        $this->addReference('ModalidadeVinculacaoDocumento-'.$modalidadeVinculacaoDocumento->getValor(), $modalidadeVinculacaoDocumento);

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
        return ['prod', 'dev', 'test'];
    }
}
