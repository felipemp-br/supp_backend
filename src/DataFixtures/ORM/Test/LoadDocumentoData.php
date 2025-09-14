<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;

/**
 * Class LoadDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        // Persist entity
        $manager->persist($documento);

        // Create reference for later usage
        $this->addReference(
            'Documento-TEMPLATE-DESPACHO-TESTE',
            $documento
        );

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));
        $documento->setTarefaOrigem($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $documento->setProcessoOrigem($this->getReference('Processo-00100000001202088', Processo::class));
        $documento->setSetorOrigem($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));

        // Persist entity
        $manager->persist($documento);

        // Create reference for later usage
        $this->addReference(
            'Documento-TEMPLATE DESPACHO2',
            $documento
        );

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        // Persist entity
        $manager->persist($documento);

        // Create reference for later usage
        $this->addReference(
            'Documento-MODELO DESPACHO DE APROVAÇÃO2',
            $documento
        );

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-OFÍCIO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        // Persist entity
        $manager->persist($documento);

        // Create reference for later usage
        $this->addReference(
            'Documento-MODELO OFÍCIO EM BRANCO2',
            $documento
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
        return ['test'];
    }
}
