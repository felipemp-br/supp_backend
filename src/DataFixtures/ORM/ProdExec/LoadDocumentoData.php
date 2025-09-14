<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
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
        $documento->setTipoDocumento($this->getReference('TipoDocumento-OFÍCIO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_OFICIO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_DESPACHO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_DESPACHO_NUMERADO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-MEMORANDO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_MEMORANDO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-EXPOSIÇÃO DE MOTIVOS', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_EXPOSICAO_MOTIVOS', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-NOTA TÉCNICA', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_NOTA_TECNICA', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-MENSAGEM', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_MENSAGEM', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-PORTARIA', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-TEMPLATE_PORTARIA', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-OFÍCIO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_OFICIO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_DESPACHO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_DESPACHO_NUMERADO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-MEMORANDO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_MEMORANDO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-EXPOSIÇÃO DE MOTIVOS', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_EXPOSICAO_MOTIVOS', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-NOTA TÉCNICA', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_NOTA_TECNICA', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-MENSAGEM', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_MENSAGEM', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-PORTARIA', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_PORTARIA', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-DESPACHO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_DESPACHO_EM_BRANCO', $documento);

        $documento = new Documento();
        $documento->setTipoDocumento($this->getReference('TipoDocumento-OFÍCIO', TipoDocumento::class));
        $documento->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $manager->persist($documento);

        $this->addReference('Documento-MODELO_OFICIO_EM_BRANCO', $documento);

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
        return ['prodexec'];
    }
}
