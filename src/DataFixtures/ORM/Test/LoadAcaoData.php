<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAcaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAcaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAcaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $acao = new Acao();
        $acao->setContexto('Ação 0001');
        $acao->setEtiqueta($this->getReference('Etiqueta-LEMBRETE', Etiqueta::class));
        $acao->setModalidadeAcaoEtiqueta(
            $this->getReference('ModalidadeAcaoEtiqueta-MINUTA', ModalidadeAcaoEtiqueta::class)
        );
        $acao->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));
        $acao->setCriadoEm(DateTime::createFromFormat('Y-m-d', '2021-05-30'));

        // Persist entity
        $manager->persist($acao);

        $acao = new Acao();
        $acao->setContexto('Ação 0002');
        $acao->setEtiqueta($this->getReference('Etiqueta-SIGILOSO', Etiqueta::class));
        $acao->setModalidadeAcaoEtiqueta(
            $this->getReference('ModalidadeAcaoEtiqueta-OFÍCIO', ModalidadeAcaoEtiqueta::class)
        );
        $acao->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));
        $acao->setCriadoEm(DateTime::createFromFormat('Y-m-d', '1999-05-30'));

        // Persist entity
        $manager->persist($acao);

        $acao = new Acao();
        $acao->setContexto('Ação 0003');
        $acao->setEtiqueta($this->getReference('Etiqueta-EM TRAMITAÇÃO', Etiqueta::class));
        $acao->setModalidadeAcaoEtiqueta(
            $this->getReference('ModalidadeAcaoEtiqueta-COMPARTILHAMENTO', ModalidadeAcaoEtiqueta::class)
        );
        $acao->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));
        $acao->setCriadoEm(DateTime::createFromFormat('Y-m-d', '2021-05-30'));

        // Persist entity
        $manager->persist($acao);

        $acao = new Acao();
        $acao->setContexto('Ação 0004');
        $acao->setEtiqueta($this->getReference('Etiqueta-REDISTRIBUÍDA', Etiqueta::class));
        $acao->setModalidadeAcaoEtiqueta(
            $this->getReference('ModalidadeAcaoEtiqueta-DISTRIBUIÇÃO AUTOMÁTICA', ModalidadeAcaoEtiqueta::class)
        );
        $acao->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));
        $acao->setCriadoEm(DateTime::createFromFormat('Y-m-d', '2021-05-30'));

        // Persist entity
        $manager->persist($acao);

        $acao = new Acao();
        $acao->setContexto('Ação 0005');
        $acao->setEtiqueta($this->getReference('Etiqueta-OFÍCIO VENCIDO', Etiqueta::class));
        $acao->setModalidadeAcaoEtiqueta(
            $this->getReference('ModalidadeAcaoEtiqueta-OFÍCIO', ModalidadeAcaoEtiqueta::class)
        );
        $acao->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));
        $acao->setCriadoEm(DateTime::createFromFormat('Y-m-d', '1999-05-30'));

        // Persist entity
        $manager->persist($acao);

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
