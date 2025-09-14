<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class LoadProcessoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadProcessoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $processo = new Processo();
        $processo->setUnidadeArquivistica(10);
        $processo->setTipoProtocolo(110);
        $processo->setSemValorEconomico(false);
        $processo->setProtocoloEletronico(false);
        $processo->setNUP('00100000001202088');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setVisibilidadeExterna(true);
        $processo->setDataHoraAbertura(DateTime::createFromFormat('Y-m-d', '2021-01-05'));
        $processo->setTitulo('TESTE_1');
        $processo->setChaveAcesso('TESTE_1');
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-FÍSICO', ModalidadeMeio::class));
        $processo->setClassificacao($this->getReference('Classificacao2', Classificacao::class));
        $processo->setProcedencia($this->getReference('Pessoa-12312312387', Pessoa::class));
        $processo->setSetorAtual($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $processo->setSetorInicial($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setUnidadeArquivistica(11);
        $processo->setTipoProtocolo(111);
        $processo->setSemValorEconomico(false);
        $processo->setProtocoloEletronico(false);
        $processo->setNUP('00100000002202022');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setVisibilidadeExterna(true);
        $processo->setDataHoraAbertura(DateTime::createFromFormat('Y-m-d', '2021-01-05'));
        $processo->setTitulo('TESTE_2');
        $processo->setChaveAcesso('TESTE_2');
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setClassificacao($this->getReference('Classificacao2', Classificacao::class));
        $processo->setProcedencia($this->getReference('Pessoa-12312312387', Pessoa::class));
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setUnidadeArquivistica(11);
        $processo->setTipoProtocolo(111);
        $processo->setSemValorEconomico(false);
        $processo->setProtocoloEletronico(false);
        $processo->setNUP('00100000005202066');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setVisibilidadeExterna(true);
        $processo->setDataHoraAbertura(DateTime::createFromFormat('Y-m-d', '2021-01-05'));
        $processo->setTitulo('TESTE_TRAMITAÇÃO');
        $processo->setChaveAcesso('TESTE_TRAMITAÇÃO');
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setClassificacao($this->getReference('Classificacao2', Classificacao::class));
        $processo->setProcedencia($this->getReference('Pessoa-12312312387', Pessoa::class));
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setUnidadeArquivistica(11);
        $processo->setTipoProtocolo(111);
        $processo->setSemValorEconomico(false);
        $processo->setProtocoloEletronico(false);
        $processo->setNUP('00100000003202077');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setVisibilidadeExterna(true);
        $processo->setDataHoraAbertura(DateTime::createFromFormat('Y-m-d', '2021-01-05'));
        $processo->setTitulo('TESTE_3');
        $processo->setChaveAcesso('TESTE_3');
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setClassificacao($this->getReference('Classificacao2', Classificacao::class));
        $processo->setProcedencia($this->getReference('Pessoa-12312312387', Pessoa::class));
        $processo->setSetorAtual($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setUnidadeArquivistica(10);
        $processo->setTipoProtocolo(110);
        $processo->setSemValorEconomico(false);
        $processo->setProtocoloEletronico(false);
        $processo->setNUP('00100000004202011');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setVisibilidadeExterna(true);
        $processo->setDataHoraAbertura(DateTime::createFromFormat('Y-m-d', '2021-01-05'));
        $processo->setTitulo('TESTE_4');
        $processo->setChaveAcesso('TESTE_4');
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-FÍSICO', ModalidadeMeio::class));
        $processo->setClassificacao($this->getReference('Classificacao2', Classificacao::class));
        $processo->setProcedencia($this->getReference('Pessoa-12312312387', Pessoa::class));
        $processo->setSetorAtual($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $processo->setSetorInicial($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
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
        return ['test'];
    }
}
