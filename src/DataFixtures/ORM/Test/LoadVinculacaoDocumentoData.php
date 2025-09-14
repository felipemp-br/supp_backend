<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoDocumento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;

/**
 * Class LoadVinculacaoDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoDocumento = new VinculacaoDocumento();
        $vinculacaoDocumento->setDocumento(
            $this->getReference('Documento-MODELO DESPACHO EM BRANCO', Documento::class)
        );
        $vinculacaoDocumento->setDocumentoVinculado(
            $this->getReference('Documento-MODELO OFÍCIO EM BRANCO', Documento::class)
        );
        $vinculacaoDocumento->setModalidadeVinculacaoDocumento(
            $this->getReference('ModalidadeVinculacaoDocumento-ANEXO', ModalidadeVinculacaoDocumento::class)
        );

        // Persist entity
        $manager->persist($vinculacaoDocumento);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
