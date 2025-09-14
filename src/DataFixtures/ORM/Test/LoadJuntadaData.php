<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadJuntadaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Atividade;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\Volume;

/**
 * Class LoadJuntadaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadJuntadaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $juntada = new Juntada();

        $juntada->setAtivo(true);
        $juntada->setVinculada(true);
        $juntada->setDescricao('TESTE_11');
        $juntada->setNumeracaoSequencial(1);
        $juntada->setAtividade($this->getReference('Atividade-TESTE_1', Atividade::class));
        $juntada->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $juntada->setDocumentoJuntadaAtual($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $juntada->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $juntada->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $juntada->setVolume($this->getReference('Volume-100', Volume::class));
        $juntada->setAtualizadoPor($this->getReference('Usuario-00000000004', Usuario::class));
        $juntada->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($juntada);

        // Create reference for later usage
        $this->addReference('Juntada-'.$juntada->getDescricao(), $juntada);

        $juntada = new Juntada();

        $juntada->setAtivo(true);
        $juntada->setVinculada(true);
        $juntada->setDescricao('TESTE_12');
        $juntada->setNumeracaoSequencial(2);
        $juntada->setAtividade($this->getReference('Atividade-TESTE_1', Atividade::class));
        $juntada->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $juntada->setDocumentoAvulso($this->getReference('DocumentoAvulso-TESTE_1', DocumentoAvulso::class));
        $juntada->setDocumentoJuntadaAtual($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));
        $juntada->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $juntada->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $juntada->setVolume($this->getReference('Volume-200', Volume::class));
        $juntada->setAtualizadoPor($this->getReference('Usuario-00000000004', Usuario::class));
        $juntada->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($juntada);

        // Create reference for later usage
        $this->addReference('Juntada-'.$juntada->getDescricao(), $juntada);

        $juntada = new Juntada();

        $juntada->setAtivo(true);
        $juntada->setVinculada(true);
        $juntada->setDescricao('TESTE_13');
        $juntada->setNumeracaoSequencial(3);
        $juntada->setAtividade($this->getReference('Atividade-TESTE_1', Atividade::class));
        $juntada->setDocumento($this->getReference('Documento-TEMPLATE-DESPACHO-TESTE', Documento::class));
        $juntada->setDocumentoAvulso($this->getReference('DocumentoAvulso-TESTE_1', DocumentoAvulso::class));
        $juntada->setDocumentoJuntadaAtual($this->getReference('Documento-TEMPLATE-DESPACHO-TESTE', Documento::class));
        $juntada->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $juntada->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $juntada->setVolume($this->getReference('Volume-300', Volume::class));
        $juntada->setAtualizadoPor($this->getReference('Usuario-00000000004', Usuario::class));
        $juntada->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($juntada);

        // Create reference for later usage
        $this->addReference('Juntada-'.$juntada->getDescricao(), $juntada);

        // Flush database changes
        $manager->flush();
    }

    /***
     * Get the order of this fixture.
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
