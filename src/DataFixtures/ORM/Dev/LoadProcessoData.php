<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

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
        $processo->setChaveAcesso('1d1d673f');
        $processo->setClassificacao($this->getReference('Classificacao4', Classificacao::class));
        $processo->setDataHoraAbertura(new DateTime('now'));
        $processo->setDescricao('PROCESSO PLAYWRIGHT 1');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setNUP('00100000001202321');
        $processo->setProcedencia($this->getReference('Pessoa-26994558000123', Pessoa::class));
        $processo->setProtocoloEletronico(true);
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setTipoProtocolo(1);
        $processo->setTitulo('PROCESSO PLAYWRIGHT 1');
        $processo->setUnidadeArquivistica(1);
        $processo->setVisibilidadeExterna(false);

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setChaveAcesso('7ae7694a1');
        $processo->setClassificacao($this->getReference('Classificacao4', Classificacao::class));
        $processo->setDataHoraAbertura(new DateTime('now'));
        $processo->setDescricao('PROCESSO PLAYWRIGHT 2');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setNUP('00100000002202375');
        $processo->setProcedencia($this->getReference('Pessoa-26994558000123', Pessoa::class));
        $processo->setProtocoloEletronico(true);
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setTipoProtocolo(1);
        $processo->setTitulo('PROCESSO PLAYWRIGHT 2');
        $processo->setUnidadeArquivistica(1);
        $processo->setVisibilidadeExterna(false);

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setChaveAcesso('0f3c6956');
        $processo->setClassificacao($this->getReference('Classificacao4', Classificacao::class));
        $processo->setDataHoraAbertura(new DateTime('now'));
        $processo->setDescricao('PROCESSO PLAYWRIGHT 3');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setNUP('00100000003202310');
        $processo->setProcedencia($this->getReference('Pessoa-26994558000123', Pessoa::class));
        $processo->setProtocoloEletronico(true);
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setTipoProtocolo(1);
        $processo->setTitulo('PROCESSO PLAYWRIGHT 3');
        $processo->setUnidadeArquivistica(1);
        $processo->setVisibilidadeExterna(false);

        // Persist entity
        $manager->persist($processo);

        // Create reference for later usage
        $this->addReference(
            'Processo-'.$processo->getNUP(),
            $processo
        );

        $processo = new Processo();
        $processo->setChaveAcesso('89c246298');
        $processo->setClassificacao($this->getReference('Classificacao4', Classificacao::class));
        $processo->setDataHoraAbertura(new DateTime('now'));
        $processo->setDescricao('PROCESSO PLAYWRIGHT 4');
        $processo->setEspecieProcesso($this->getReference('EspecieProcesso-COMUM', EspecieProcesso::class));
        $processo->setModalidadeFase($this->getReference('ModalidadeFase-CORRENTE', ModalidadeFase::class));
        $processo->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));
        $processo->setNUP('00100000003202364');
        $processo->setProcedencia($this->getReference('Pessoa-26994558000123', Pessoa::class));
        $processo->setProtocoloEletronico(true);
        $processo->setSetorAtual($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setSetorInicial($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $processo->setTipoProtocolo(1);
        $processo->setTitulo('PROCESSO PLAYWRIGHT 4');
        $processo->setUnidadeArquivistica(1);
        $processo->setVisibilidadeExterna(false);

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
        return ['dev'];
    }
}
