<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEspecieProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;

/**
 * Class LoadEspecieProcessoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieProcessoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACESSO À INFORMAÇÃO: DEMANDA DO E-SIC');
        $especieProcesso->setTitulo('ACESSO À INFORMAÇÃO: DEMANDA DO E-SIC');
        $especieProcesso->setDescricao('TRATAMENTO DE DEMANDAS E RECURSOS DO E-SIC.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACOMPANHAMENTO LEGISLATIVO: CÂMARA DOS DEPUTADOS');
        $especieProcesso->setTitulo('ACOMPANHAMENTO LEGISLATIVO: CÂMARA DOS DEPUTADOS');
        $especieProcesso->setDescricao('ACOMPANHAR PROCESSO LEGISLATIVO A FIM DE PROMOVER OS INTERESSES DO ÓRGÃO, INCLUINDO ENCAMINHAMENTO DE PARECERES SOBRE PROJETOS DE LEI, INTERAÇÃO PRESENCIAL COM PARLAMENTARES E PARTICIPAÇÃO EM AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-013.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACOMPANHAMENTO LEGISLATIVO: CONGRESSO NACIONAL');
        $especieProcesso->setTitulo('ACOMPANHAMENTO LEGISLATIVO: CONGRESSO NACIONAL');
        $especieProcesso->setDescricao('ACOMPANHAR PROCESSO LEGISLATIVO A FIM DE PROMOVER OS INTERESSES DO ÓRGÃO, INCLUINDO ENCAMINHAMENTO DE PARECERES SOBRE PROJETOS DE LEI, INTERAÇÃO PRESENCIAL COM PARLAMENTARES E PARTICIPAÇÃO EM AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-013.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACOMPANHAMENTO LEGISLATIVO: ESTADUAL/DISTRITAL');
        $especieProcesso->setTitulo('ACOMPANHAMENTO LEGISLATIVO: ESTADUAL/DISTRITAL');
        $especieProcesso->setDescricao('ACOMPANHAR PROCESSO LEGISLATIVO A FIM DE PROMOVER OS INTERESSES DO ÓRGÃO, INCLUINDO ENCAMINHAMENTO DE PARECERES SOBRE PROJETOS DE LEI, INTERAÇÃO PRESENCIAL COM PARLAMENTARES E PARTICIPAÇÃO EM AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-013.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACOMPANHAMENTO LEGISLATIVO: MUNICIPAL');
        $especieProcesso->setTitulo('ACOMPANHAMENTO LEGISLATIVO: MUNICIPAL');
        $especieProcesso->setDescricao('ACOMPANHAR PROCESSO LEGISLATIVO A FIM DE PROMOVER OS INTERESSES DO ÓRGÃO, INCLUINDO ENCAMINHAMENTO DE PARECERES SOBRE PROJETOS DE LEI, INTERAÇÃO PRESENCIAL COM PARLAMENTARES E PARTICIPAÇÃO EM AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-013.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL');
        $especieProcesso->setTitulo('ACOMPANHAMENTO LEGISLATIVO: SENADO FEDERAL');
        $especieProcesso->setDescricao('ACOMPANHAR PROCESSO LEGISLATIVO A FIM DE PROMOVER OS INTERESSES DO ÓRGÃO, INCLUINDO ENCAMINHAMENTO DE PARECERES SOBRE PROJETOS DE LEI, INTERAÇÃO PRESENCIAL COM PARLAMENTARES E PARTICIPAÇÃO EM AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-013.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: COBRANÇA');
        $especieProcesso->setTitulo('ARRECADAÇÃO: COBRANÇA');
        $especieProcesso->setDescricao('ARRECADAÇÃO: COBRANÇA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: CUMPRIMENTO DE AÇÃO JUDICIAL');
        $especieProcesso->setTitulo('ARRECADAÇÃO: CUMPRIMENTO DE AÇÃO JUDICIAL');
        $especieProcesso->setDescricao('ARRECADAÇÃO: CUMPRIMENTO DE AÇÃO JUDICIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-004.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: ENCAMINHAMENTO PARA DÍVIDA ATIVA');
        $especieProcesso->setTitulo('ARRECADAÇÃO: ENCAMINHAMENTO PARA DÍVIDA ATIVA');
        $especieProcesso->setDescricao('ARRECADAÇÃO: ENCAMINHAMENTO PARA DÍVIDA ATIVA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-004.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setTitulo('ARRECADAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setDescricao('ARRECADAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: NOTIFICAÇÃO/COMUNICADO');
        $especieProcesso->setTitulo('ARRECADAÇÃO: NOTIFICAÇÃO/COMUNICADO');
        $especieProcesso->setDescricao('ARRECADAÇÃO: NOTIFICAÇÃO/COMUNICADO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: PARCELAMENTO ADMINISTRATIVO');
        $especieProcesso->setTitulo('ARRECADAÇÃO: PARCELAMENTO ADMINISTRATIVO');
        $especieProcesso->setDescricao('ARRECADAÇÃO: PARCELAMENTO ADMINISTRATIVO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: RECEITA');
        $especieProcesso->setTitulo('ARRECADAÇÃO: RECEITA');
        $especieProcesso->setDescricao('ARRECADAÇÃO: RECEITA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: REGULARIZAÇÃO DE INDÉBITOS');
        $especieProcesso->setTitulo('ARRECADAÇÃO: REGULARIZAÇÃO DE INDÉBITOS');
        $especieProcesso->setDescricao('ARRECADAÇÃO: REGULARIZAÇÃO DE INDÉBITOS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: RESTITUIÇÃO/COMPENSAÇÃO');
        $especieProcesso->setTitulo('ARRECADAÇÃO: RESTITUIÇÃO/COMPENSAÇÃO');
        $especieProcesso->setDescricao('ARRECADAÇÃO: RESTITUIÇÃO/COMPENSAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ARRECADAÇÃO: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setTitulo('ARRECADAÇÃO: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setDescricao('ARRECADAÇÃO: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-004.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUNICAÇÃO: EVENTO INSTITUCIONAL PÚBLICO EXTERNO');
        $especieProcesso->setTitulo('COMUNICAÇÃO: EVENTO INSTITUCIONAL PÚBLICO EXTERNO');
        $especieProcesso->setDescricao('PROCESSO PARA RECEBIMENTO DE PEDIDOS DE APOIO PARA A REALIZAÇÃO DE EVENTOS INSTITUCIONAIS DIRECIONADOS AO PÚBLICO EXTERNO, POR EXEMPLO, AUDIÊNCIAS PÚBLICAS E SESSÕES PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-019.113', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUNICAÇÃO: EVENTO INSTITUCIONAL PÚBLICO INTERNO');
        $especieProcesso->setTitulo('COMUNICAÇÃO: EVENTO INSTITUCIONAL PÚBLICO INTERNO');
        $especieProcesso->setDescricao('PEDIDOS DE APOIO PARA A REALIZAÇÃO DE EVENTOS INSTITUCIONAIS DIRECIONADOS AO PÚBLICO INTERNO, POR EXEMPLO, ANIVERSÁRIO DO ÓRGÃO OU EVENTOS DA SEMANA DO SERVIDOR PÚBLICO.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-019.113', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUNICAÇÃO: PEDIDO DE APOIO INSTITUCIONAL');
        $especieProcesso->setTitulo('COMUNICAÇÃO: PEDIDO DE APOIO INSTITUCIONAL');
        $especieProcesso->setDescricao('PEDIDOS PARA UTILIZAÇÃO DA LOGOMARCA DO ÓRGÃO EM EVENTOS INSTITUCIONAIS PROMOVIDOS POR TERCEIROS, SEJAM PÚBLICOS OU PRIVADOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-019.113', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUNICAÇÃO: PUBLICIDADE INSTITUCIONAL');
        $especieProcesso->setTitulo('COMUNICAÇÃO: PUBLICIDADE INSTITUCIONAL');
        $especieProcesso->setDescricao('DEMANDAS PARA A REALIZAÇÃO DE AÇÕES DE COMUNICAÇÃO PARA DISSEMINAR - PARA OS PÚBLICOS INTERNO OU EXTERNO - INFORMAÇÕES SOBRE DETERMINADOS TEMAS DE MAIOR RELEVÂNCIA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-019.113', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('COMUNICAÇÃO: PUBLICIDADE LEGAL');
        $especieProcesso->setTitulo('COMUNICAÇÃO: PUBLICIDADE LEGAL');
        $especieProcesso->setDescricao('DEMANDAS PARA PUBLICAÇÃO EM VEÍCULOS DE COMUNICAÇÃO DE GRANDE CIRCULAÇÃO, PARA FINS DE PUBLICIDADE EXIGIDA POR LEI, POR EXEMPLO, AVISOS DE REALIZAÇÃO DE PREGÕES E DE AUDIÊNCIAS PÚBLICAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-069.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: ANÁLISE CONTÁBIL');
        $especieProcesso->setTitulo('CONTABILIDADE: ANÁLISE CONTÁBIL');
        $especieProcesso->setDescricao('CONTABILIDADE: ANÁLISE CONTÁBIL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.23', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: CONFORMIDADE DE GESTÃO');
        $especieProcesso->setTitulo('CONTABILIDADE: CONFORMIDADE DE GESTÃO');
        $especieProcesso->setDescricao('CONTABILIDADE: CONFORMIDADE DE GESTÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.02', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: CONTRATOS E GARANTIAS');
        $especieProcesso->setTitulo('CONTABILIDADE: CONTRATOS E GARANTIAS');
        $especieProcesso->setDescricao('CONTABILIDADE: CONTRATOS E GARANTIAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: DIRF');
        $especieProcesso->setTitulo('CONTABILIDADE: DIRF');
        $especieProcesso->setDescricao('CONTABILIDADE: DIRF');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.185', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: ENCERRAMENTO DO EXERCÍCIO');
        $especieProcesso->setTitulo('CONTABILIDADE: ENCERRAMENTO DO EXERCÍCIO');
        $especieProcesso->setDescricao('CONTABILIDADE: ENCERRAMENTO DO EXERCÍCIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.03', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: FECHAMENTO CONTÁBIL - ESTOQUE');
        $especieProcesso->setTitulo('CONTABILIDADE: FECHAMENTO CONTÁBIL - ESTOQUE');
        $especieProcesso->setDescricao('CONTABILIDADE: FECHAMENTO CONTÁBIL - ESTOQUE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.23', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: FECHAMENTO CONTÁBIL PATRIMONIAL');
        $especieProcesso->setTitulo('CONTABILIDADE: FECHAMENTO CONTÁBIL PATRIMONIAL');
        $especieProcesso->setDescricao('CONTABILIDADE: FECHAMENTO CONTÁBIL PATRIMONIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.23', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: MANUAIS');
        $especieProcesso->setTitulo('CONTABILIDADE: MANUAIS');
        $especieProcesso->setDescricao('CONTABILIDADE: MANUAIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setTitulo('CONTABILIDADE: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setDescricao('CONTABILIDADE: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONTABILIDADE: PRESTAÇÃO DE CONTAS');
        $especieProcesso->setTitulo('CONTABILIDADE: PRESTAÇÃO DE CONTAS');
        $especieProcesso->setDescricao('CONTABILIDADE: PRESTAÇÃO DE CONTAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-054.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONVÊNIOS/AJUSTES: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setTitulo('CONVÊNIOS/AJUSTES: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setDescricao('CONVÊNIOS/AJUSTES: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO COM REPASSE');
        $especieProcesso->setTitulo('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO COM REPASSE');
        $especieProcesso->setDescricao('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO COM REPASSE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO SEM REPASSE');
        $especieProcesso->setTitulo('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO SEM REPASSE');
        $especieProcesso->setDescricao('CONVÊNIOS/AJUSTES: FORMALIZAÇÃO/ALTERAÇÃO SEM REPASSE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: ANÁLISE PRESCRICIONAL DE PROCESSO');
        $especieProcesso->setTitulo('CORREGEDORIA: ANÁLISE PRESCRICIONAL DE PROCESSO');
        $especieProcesso->setDescricao('CORREGEDORIA: ANÁLISE PRESCRICIONAL DE PROCESSO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: AVALIAÇÃO PARA ESTABILIDADE');
        $especieProcesso->setTitulo('CORREGEDORIA: AVALIAÇÃO PARA ESTABILIDADE');
        $especieProcesso->setDescricao('CORREGEDORIA: AVALIAÇÃO PARA ESTABILIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: CORREIÇÃO');
        $especieProcesso->setTitulo('CORREGEDORIA: CORREIÇÃO');
        $especieProcesso->setDescricao('CORREGEDORIA: CORREIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: INVESTIGAÇÃO PRELIMINAR');
        $especieProcesso->setTitulo('CORREGEDORIA: INVESTIGAÇÃO PRELIMINAR');
        $especieProcesso->setDescricao('CORREGEDORIA: INVESTIGAÇÃO PRELIMINAR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: PROCEDIMENTO GERAL');
        $especieProcesso->setTitulo('CORREGEDORIA: PROCEDIMENTO GERAL');
        $especieProcesso->setDescricao('CORREGEDORIA: PROCEDIMENTO GERAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: PROCESSO ADMINISTRATIVO DISCIPLINAR');
        $especieProcesso->setTitulo('CORREGEDORIA: PROCESSO ADMINISTRATIVO DISCIPLINAR');
        $especieProcesso->setDescricao('CORREGEDORIA: PROCESSO ADMINISTRATIVO DISCIPLINAR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('CORREGEDORIA: SINDICÂNCIA PUNITIVA');
        $especieProcesso->setTitulo('CORREGEDORIA: SINDICÂNCIA PUNITIVA');
        $especieProcesso->setDescricao('CORREGEDORIA: SINDICÂNCIA PUNITIVA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: CIDADÃO (PESSOA FÍSICA)');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: CIDADÃO (PESSOA FÍSICA)');
        $especieProcesso->setDescricao('DEMANDA EXTERNA: CIDADÃO (PESSOA FÍSICA)');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: DEPUTADO ESTADUAL/DISTRITAL');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: DEPUTADO ESTADUAL/DISTRITAL');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES PARLAMENTARES, COMO PEDIDOS DE INFORMAÇÃO, CONSULTA A PROCESSOS, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO E VISITA TÉCNICA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: DEPUTADO FEDERAL');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: DEPUTADO FEDERAL');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES PARLAMENTARES, COMO PEDIDOS DE INFORMAÇÃO, CONSULTA A PROCESSOS, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO E VISITA TÉCNICA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: JUDICIÁRIO');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: JUDICIÁRIO');
        $especieProcesso->setDescricao('DEMANDA EXTERNA: JUDICIÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-004.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO ESTADUAL');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO ESTADUAL');
        $especieProcesso->setDescricao('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO ESTADUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO FEDERAL');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO FEDERAL');
        $especieProcesso->setDescricao('DEMANDA EXTERNA: MINISTÉRIO PÚBLICO FEDERAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS ESTADUAIS');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS ESTADUAIS');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES INSTITUCIONAIS DE ÓRGÃOS GOVERNAMENTAIS ESTADUAIS, COMO PEDIDOS DE INFORMAÇÃO, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO, VISITA TÉCNICA, REUNIÕES, ESCLARECIMENTO DE DÚVIDAS OU OUTROS QUESTIONAMENTOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS FEDERAIS');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS FEDERAIS');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES INSTITUCIONAIS DE ÓRGÃOS GOVERNAMENTAIS FEDERAIS, COMO PEDIDOS DE INFORMAÇÃO, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO, VISITA TÉCNICA, REUNIÕES, ESCLARECIMENTO DE DÚVIDAS OU OUTROS QUESTIONAMENTOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS MUNICIPAIS');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: ORGÃOS GOVERNAMENTAIS MUNICIPAIS');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES INSTITUCIONAIS DE ÓRGÃOS GOVERNAMENTAIS MUNICIPAIS, COMO PEDIDOS DE INFORMAÇÃO, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO, VISITA TÉCNICA, REUNIÕES, ESCLARECIMENTO DE DÚVIDAS OU OUTROS QUESTIONAMENTOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: OUTRAS ENTIDADES PRIVADAS');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: OUTRAS ENTIDADES PRIVADAS');
        $especieProcesso->setDescricao('DEMANDA EXTERNA: OUTRAS ENTIDADES PRIVADAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: OUTROS ORGÃOS PÚBLICOS');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: OUTROS ORGÃOS PÚBLICOS');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES INSTITUCIONAIS DE OUTROS ÓRGÃOS GOVERNAMENTAIS, COMO PEDIDOS DE INFORMAÇÃO, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO, VISITA TÉCNICA, REUNIÕES, ESCLARECIMENTO DE DÚVIDAS OU OUTROS QUESTIONAMENTOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: SENADOR');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: SENADOR');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES PARLAMENTARES, COMO PEDIDOS DE INFORMAÇÃO, CONSULTA A PROCESSOS, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO E VISITA TÉCNICA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('DEMANDA EXTERNA: VEREADOR/CÂMARA MUNICIPAL');
        $especieProcesso->setTitulo('DEMANDA EXTERNA: VEREADOR/CÂMARA MUNICIPAL');
        $especieProcesso->setDescricao('ATENDER SOLICITAÇÕES PARLAMENTARES, COMO PEDIDOS DE INFORMAÇÃO, CONSULTA A PROCESSOS, AGENDA COM PRESIDENTE OU DEMAIS GESTORES DO ÓRGÃO E VISITA TÉCNICA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ÉTICA: ANÁLISE DE CONFLITO DE INTERESSE');
        $especieProcesso->setTitulo('ÉTICA: ANÁLISE DE CONFLITO DE INTERESSE');
        $especieProcesso->setDescricao('ÉTICA: ANÁLISE DE CONFLITO DE INTERESSE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ÉTICA: PROCESSO DE APURAÇÃO ÉTICA');
        $especieProcesso->setTitulo('ÉTICA: PROCESSO DE APURAÇÃO ÉTICA');
        $especieProcesso->setDescricao('ÉTICA: PROCESSO DE APURAÇÃO ÉTICA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('FINANÇAS: EXECUÇÃO FINANCEIRA');
        $especieProcesso->setTitulo('FINANÇAS: EXECUÇÃO FINANCEIRA');
        $especieProcesso->setDescricao('FINANÇAS: EXECUÇÃO FINANCEIRA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.221', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('FINANÇAS: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setTitulo('FINANÇAS: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setDescricao('FINANÇAS: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('FINANÇAS: REEMBOLSO/RESSARCIMENTO');
        $especieProcesso->setTitulo('FINANÇAS: REEMBOLSO/RESSARCIMENTO');
        $especieProcesso->setDescricao('FINANÇAS: REEMBOLSO/RESSARCIMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.73', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: ARRECADAÇÃO');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: ARRECADAÇÃO');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: ARRECADAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.211', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: AVALIAÇÃO/DESTINAÇÃO DE DOCUMENTOS');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: AVALIAÇÃO/DESTINAÇÃO DE DOCUMENTOS');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: AVALIAÇÃO/DESTINAÇÃO DE DOCUMENTOS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.51', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: CADASTRO DE USUÁRIO EXTERNO NO SEI');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: CADASTRO DE USUÁRIO EXTERNO NO SEI');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: CADASTRO DE USUÁRIO EXTERNO NO SEI');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-060.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: CONTROLE DE MALOTE');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: CONTROLE DE MALOTE');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: CONTROLE DE MALOTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-065.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: CREDENCIAMENTO DE SEGURANÇA');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: CREDENCIAMENTO DE SEGURANÇA');
        $especieProcesso->setDescricao('CREDENCIAMENTO PARA ACESSO A DOCUMENTOS CLASSIFICADOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-060.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: GESTÃO DOCUMENTAL');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: GESTÃO DOCUMENTAL');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: GESTÃO DOCUMENTAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-060.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-060.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: RECEBIMENTO DE PROCESSO EXTERNO');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: RECEBIMENTO DE PROCESSO EXTERNO');
        $especieProcesso->setDescricao('APLICADO AUTOMATICAMENTE EM PROCESSOS RECEBIDOS PELO SEI FEDERAÇÃO.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-065.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: RECONSTITUIÇÃO DOCUMENTAL');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: RECONSTITUIÇÃO DOCUMENTAL');
        $especieProcesso->setDescricao('RECONSTITUIÇÃO DE PROCESSOS OU DOCUMENTOS PERDIDOS OU DANIFICADOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: ROL ANUAL DE INFORMAÇÕES CLASSIFICADAS');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: ROL ANUAL DE INFORMAÇÕES CLASSIFICADAS');
        $especieProcesso->setDescricao('PROCESSO DE DIVULGAÇÃO ANUAL DO ROL DE INFORMAÇÕES CLASSIFICADAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DA INFORMAÇÃO: SEGURANÇA DA INFORMAÇÃO E COMUNICAÇÕES');
        $especieProcesso->setTitulo('GESTÃO DA INFORMAÇÃO: SEGURANÇA DA INFORMAÇÃO E COMUNICAÇÕES');
        $especieProcesso->setDescricao('GESTÃO DA INFORMAÇÃO: SEGURANÇA DA INFORMAÇÃO E COMUNICAÇÕES');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-066.32', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: ACOMPANHAMENTO DA EXECUÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: ACRÉSCIMO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: ACRÉSCIMO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: ACRÉSCIMO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: ALTERAÇÕES CONTRATUAIS CONJUNTAS');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: ALTERAÇÕES CONTRATUAIS CONJUNTAS');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: ALTERAÇÕES CONTRATUAIS CONJUNTAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: APLICAÇÃO DE SANÇÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: APLICAÇÃO DE SANÇÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: APLICAÇÃO DE SANÇÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-018', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: CONSULTA À PROCURADORIA/CONJUR');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: CONSULTA À PROCURADORIA/CONJUR');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: CONSULTA À PROCURADORIA/CONJUR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: EXECUÇÃO DE GARANTIA');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: EXECUÇÃO DE GARANTIA');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: EXECUÇÃO DE GARANTIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-018', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: OUTRAS ALTERAÇÕES CONTRATUAIS NÃO RELACIONADAS');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: OUTRAS ALTERAÇÕES CONTRATUAIS NÃO RELACIONADAS');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: OUTRAS ALTERAÇÕES CONTRATUAIS NÃO RELACIONADAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: PAGAMENTO DIRETO A TERCEIROS');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: PAGAMENTO DIRETO A TERCEIROS');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: PAGAMENTO DIRETO A TERCEIROS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-018', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: PROCESSO DE PAGAMENTO');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: PROCESSO DE PAGAMENTO');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: PROCESSO DE PAGAMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-018', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: PRORROGAÇÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: PRORROGAÇÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: PRORROGAÇÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: REAJUSTE OU REPACTUAÇÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: REAJUSTE OU REPACTUAÇÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: REAJUSTE OU REPACTUAÇÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: RESCISÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: RESCISÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: RESCISÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: REVISÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: REVISÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: REVISÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE CONTRATO: SUPRESSÃO CONTRATUAL');
        $especieProcesso->setTitulo('GESTÃO DE CONTRATO: SUPRESSÃO CONTRATUAL');
        $especieProcesso->setDescricao('GESTÃO DE CONTRATO: SUPRESSÃO CONTRATUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE PROCESSOS: MAPEAMENTO E MODELAGEM');
        $especieProcesso->setTitulo('GESTÃO DE PROCESSOS: MAPEAMENTO E MODELAGEM');
        $especieProcesso->setDescricao('GESTÃO DE PROCESSOS: MAPEAMENTO E MODELAGEM');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-016.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE PROJETOS: PLANEJAMENTO E EXECUÇÃO');
        $especieProcesso->setTitulo('GESTÃO DE PROJETOS: PLANEJAMENTO E EXECUÇÃO');
        $especieProcesso->setDescricao('GESTÃO DE PROJETOS: PLANEJAMENTO E EXECUÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO DE TI: CITI');
        $especieProcesso->setTitulo('GESTÃO DE TI: CITI');
        $especieProcesso->setDescricao('GESTÃO DE TI: CITI');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS EXTERNAS');
        $especieProcesso->setTitulo('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS EXTERNAS');
        $especieProcesso->setDescricao('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS EXTERNAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS INTERNAS');
        $especieProcesso->setTitulo('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS INTERNAS');
        $especieProcesso->setDescricao('GESTÃO E CONTROLE: COORDENAÇÃO - DEMANDAS INTERNAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO E CONTROLE: DEMANDAS DE ÓRGÃOS DE CONTROLE');
        $especieProcesso->setTitulo('GESTÃO E CONTROLE: DEMANDAS DE ÓRGÃOS DE CONTROLE');
        $especieProcesso->setDescricao('ADMINISTRAR DEMANDAS E ACOMPANHAR AS DELIBERAÇÕES DOS ÓRGÃOS DE CONTROLE DO GOVERNO FEDERAL.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('GESTÃO E CONTROLE: EXECUTAR AUDITORIA INTERNA');
        $especieProcesso->setTitulo('GESTÃO E CONTROLE: EXECUTAR AUDITORIA INTERNA');
        $especieProcesso->setDescricao('ANALISAR A FIDEDIGNIDADE DAS INFORMAÇÕES QUE TRAMITAM NOS PROCESSOS DO ÓRGÃO, IDENTIFICAR NECESSIDADE DE PONTOS DE CONTROLE DE NÃO CONFORMIDADES, SUAS CAUSAS, QUALIFICAR E QUANTIFICAR AS PERDAS E RECOMENDAR AÇÕES CORRETIVAS E PREVENTIVAS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('INFRAESTRUTURA: ABASTECIMENTO DE ÁGUA E ESGOTO');
        $especieProcesso->setTitulo('INFRAESTRUTURA: ABASTECIMENTO DE ÁGUA E ESGOTO');
        $especieProcesso->setDescricao('INFRAESTRUTURA: ABASTECIMENTO DE ÁGUA E ESGOTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-045.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('INFRAESTRUTURA: APOIO DE ENGENHARIA CIVIL');
        $especieProcesso->setTitulo('INFRAESTRUTURA: APOIO DE ENGENHARIA CIVIL');
        $especieProcesso->setDescricao('INFRAESTRUTURA: APOIO DE ENGENHARIA CIVIL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-043.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('INFRAESTRUTURA: FORNECIMENTO DE ENERGIA ELÉTRICA');
        $especieProcesso->setTitulo('INFRAESTRUTURA: FORNECIMENTO DE ENERGIA ELÉTRICA');
        $especieProcesso->setDescricao('INFRAESTRUTURA: FORNECIMENTO DE ENERGIA ELÉTRICA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-045.13', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: ADESÃO A ATA DE RP-NÃO PARTICIPANTE');
        $especieProcesso->setTitulo('LICITAÇÃO: ADESÃO A ATA DE RP-NÃO PARTICIPANTE');
        $especieProcesso->setDescricao('LICITAÇÃO: ADESÃO A ATA DE RP-NÃO PARTICIPANTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: ADESÃO A ATA DE RP-PARTICIPANTE');
        $especieProcesso->setTitulo('LICITAÇÃO: ADESÃO A ATA DE RP-PARTICIPANTE');
        $especieProcesso->setDescricao('LICITAÇÃO: ADESÃO A ATA DE RP-PARTICIPANTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: APLICAÇÃO DE SANÇÃO DECORRENTE DE PROCEDIMENTO LICITATÓRIO');
        $especieProcesso->setTitulo('LICITAÇÃO: APLICAÇÃO DE SANÇÃO DECORRENTE DE PROCEDIMENTO LICITATÓRIO');
        $especieProcesso->setDescricao('LICITAÇÃO: APLICAÇÃO DE SANÇÃO DECORRENTE DE PROCEDIMENTO LICITATÓRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-018', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: CONCORRÊNCIA');
        $especieProcesso->setTitulo('LICITAÇÃO: CONCORRÊNCIA');
        $especieProcesso->setDescricao('LICITAÇÃO: CONCORRÊNCIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: CONCORRÊNCIA-REGISTRO DE PREÇO');
        $especieProcesso->setTitulo('LICITAÇÃO: CONCORRÊNCIA-REGISTRO DE PREÇO');
        $especieProcesso->setDescricao('LICITAÇÃO: CONCORRÊNCIA-REGISTRO DE PREÇO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: CONCURSO');
        $especieProcesso->setTitulo('LICITAÇÃO: CONCURSO');
        $especieProcesso->setDescricao('LICITAÇÃO: CONCURSO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: CONSULTA');
        $especieProcesso->setTitulo('LICITAÇÃO: CONSULTA');
        $especieProcesso->setDescricao('LICITAÇÃO: CONSULTA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: CONVITE');
        $especieProcesso->setTitulo('LICITAÇÃO: CONVITE');
        $especieProcesso->setDescricao('LICITAÇÃO: CONVITE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: DISPENSA - ACIMA DE R$ 8 MIL');
        $especieProcesso->setTitulo('LICITAÇÃO: DISPENSA - ACIMA DE R$ 8 MIL');
        $especieProcesso->setDescricao('LICITAÇÃO: DISPENSA - ACIMA DE R$ 8 MIL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: DISPENSA - ATÉ R$ 8 MIL');
        $especieProcesso->setTitulo('LICITAÇÃO: DISPENSA - ATÉ R$ 8 MIL');
        $especieProcesso->setDescricao('LICITAÇÃO: DISPENSA - ATÉ R$ 8 MIL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: INEXIGIBILIDADE');
        $especieProcesso->setTitulo('LICITAÇÃO: INEXIGIBILIDADE');
        $especieProcesso->setDescricao('LICITAÇÃO: INEXIGIBILIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: LEILÃO');
        $especieProcesso->setTitulo('LICITAÇÃO: LEILÃO');
        $especieProcesso->setDescricao('LICITAÇÃO: LEILÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: PLANO DE AQUISIÇÕES');
        $especieProcesso->setTitulo('LICITAÇÃO: PLANO DE AQUISIÇÕES');
        $especieProcesso->setDescricao('LICITAÇÃO: PLANO DE AQUISIÇÕES');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: PREGÃO ELETRÔNICO');
        $especieProcesso->setTitulo('LICITAÇÃO: PREGÃO ELETRÔNICO');
        $especieProcesso->setDescricao('LICITAÇÃO: PREGÃO ELETRÔNICO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: PREGÃO ELETRÔNICO-REGISTRO DE PREÇO');
        $especieProcesso->setTitulo('LICITAÇÃO: PREGÃO ELETRÔNICO-REGISTRO DE PREÇO');
        $especieProcesso->setDescricao('LICITAÇÃO: PREGÃO ELETRÔNICO-REGISTRO DE PREÇO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: PREGÃO PRESENCIAL');
        $especieProcesso->setTitulo('LICITAÇÃO: PREGÃO PRESENCIAL');
        $especieProcesso->setDescricao('LICITAÇÃO: PREGÃO PRESENCIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: REGIME DIFERENCIADO DE CONTRATAÇÃO-RDC');
        $especieProcesso->setTitulo('LICITAÇÃO: REGIME DIFERENCIADO DE CONTRATAÇÃO-RDC');
        $especieProcesso->setDescricao('LICITAÇÃO: REGIME DIFERENCIADO DE CONTRATAÇÃO-RDC');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('LICITAÇÃO: TOMADA DE PREÇOS');
        $especieProcesso->setTitulo('LICITAÇÃO: TOMADA DE PREÇOS');
        $especieProcesso->setDescricao('LICITAÇÃO: TOMADA DE PREÇOS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: DESFAZIMENTO DE MATERIAL DE CONSUMO');
        $especieProcesso->setTitulo('MATERIAL: DESFAZIMENTO DE MATERIAL DE CONSUMO');
        $especieProcesso->setDescricao('MATERIAL: DESFAZIMENTO DE MATERIAL DE CONSUMO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-033.42', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: DESFAZIMENTO DE MATERIAL PERMANENTE');
        $especieProcesso->setTitulo('MATERIAL: DESFAZIMENTO DE MATERIAL PERMANENTE');
        $especieProcesso->setDescricao('MATERIAL: DESFAZIMENTO DE MATERIAL PERMANENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-033.41', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: INVENTÁRIO DE MATERIAL DE CONSUMO');
        $especieProcesso->setTitulo('MATERIAL: INVENTÁRIO DE MATERIAL DE CONSUMO');
        $especieProcesso->setDescricao('MATERIAL: INVENTÁRIO DE MATERIAL DE CONSUMO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-035', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: INVENTÁRIO DE MATERIAL PERMANENTE');
        $especieProcesso->setTitulo('MATERIAL: INVENTÁRIO DE MATERIAL PERMANENTE');
        $especieProcesso->setDescricao('MATERIAL: INVENTÁRIO DE MATERIAL PERMANENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-034', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: MOVIMENTAÇÃO DE MATERIAL DE CONSUMO');
        $especieProcesso->setTitulo('MATERIAL: MOVIMENTAÇÃO DE MATERIAL DE CONSUMO');
        $especieProcesso->setDescricao('MATERIAL: MOVIMENTAÇÃO DE MATERIAL DE CONSUMO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('MATERIAL: MOVIMENTAÇÃO DE MATERIAL PERMANENTE');
        $especieProcesso->setTitulo('MATERIAL: MOVIMENTAÇÃO DE MATERIAL PERMANENTE');
        $especieProcesso->setDescricao('MATERIAL: MOVIMENTAÇÃO DE MATERIAL PERMANENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-032.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: ACOMPANHAMENTO DE DESPESA MENSAL');
        $especieProcesso->setTitulo('ORÇAMENTO: ACOMPANHAMENTO DE DESPESA MENSAL');
        $especieProcesso->setDescricao('ORÇAMENTO: ACOMPANHAMENTO DE DESPESA MENSAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-051.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: CONTINGENCIAMENTO');
        $especieProcesso->setTitulo('ORÇAMENTO: CONTINGENCIAMENTO');
        $especieProcesso->setDescricao('ORÇAMENTO: CONTINGENCIAMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-051.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: CRÉDITOS ADICIONAIS');
        $especieProcesso->setTitulo('ORÇAMENTO: CRÉDITOS ADICIONAIS');
        $especieProcesso->setDescricao('ORÇAMENTO: CRÉDITOS ADICIONAIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-051.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: DESCENTRALIZAÇÃO DE CRÉDITOS');
        $especieProcesso->setTitulo('ORÇAMENTO: DESCENTRALIZAÇÃO DE CRÉDITOS');
        $especieProcesso->setDescricao('ORÇAMENTO: DESCENTRALIZAÇÃO DE CRÉDITOS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-051.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: MANUAIS');
        $especieProcesso->setTitulo('ORÇAMENTO: MANUAIS');
        $especieProcesso->setDescricao('ORÇAMENTO: MANUAIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('ORÇAMENTO: PROGRAMAÇÃO ORÇAMENTÁRIA');
        $especieProcesso->setTitulo('ORÇAMENTO: PROGRAMAÇÃO ORÇAMENTÁRIA');
        $especieProcesso->setDescricao('ORÇAMENTO: PROGRAMAÇÃO ORÇAMENTÁRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-051.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: AGRADECIMENTO AO ÓRGÃO');
        $especieProcesso->setTitulo('OUVIDORIA: AGRADECIMENTO AO ÓRGÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: CRÍTICA À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setTitulo('OUVIDORIA: CRÍTICA À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: DENÚNCIA CONTRA A ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setTitulo('OUVIDORIA: DENÚNCIA CONTRA A ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: ELOGIO À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setTitulo('OUVIDORIA: ELOGIO À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: PEDIDO DE INFORMAÇÃO');
        $especieProcesso->setTitulo('OUVIDORIA: PEDIDO DE INFORMAÇÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('OUVIDORIA: RECLAMAÇÃO À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setTitulo('OUVIDORIA: RECLAMAÇÃO À ATUAÇÃO DO ÓRGÃO');
        $especieProcesso->setDescricao('TIPO DE PROCESSO UTILIZADO PELO FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA. - EXCLUSIVO DA OUVIDORIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-991', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - REGISTRO');
        $especieProcesso->setTitulo('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - REGISTRO');
        $especieProcesso->setDescricao('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - REGISTRO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - CATALOGAÇÃO');
        $especieProcesso->setTitulo('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - CATALOGAÇÃO');
        $especieProcesso->setDescricao('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - CATALOGAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - INVENTÁRIO');
        $especieProcesso->setTitulo('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - INVENTÁRIO');
        $especieProcesso->setDescricao('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - INVENTÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - EMPRÉSTIMO');
        $especieProcesso->setTitulo('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - EMPRÉSTIMO');
        $especieProcesso->setDescricao('PATRIMÔNIO: COBRANÇA DE ACERVO BIBLIOGRÁFICO - EMPRÉSTIMO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – REGISTRO');
        $especieProcesso->setTitulo('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – REGISTRO');
        $especieProcesso->setDescricao('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – REGISTRO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – CATALOGAÇÃO');
        $especieProcesso->setTitulo('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – CATALOGAÇÃO');
        $especieProcesso->setDescricao('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – CATALOGAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – INVENTÁRIO');
        $especieProcesso->setTitulo('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – INVENTÁRIO');
        $especieProcesso->setDescricao('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – INVENTÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-062.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – EMPRÉSTIMO');
        $especieProcesso->setTitulo('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – EMPRÉSTIMO');
        $especieProcesso->setDescricao('PATRIMÔNIO: GESTÃO DE ACERVO BIBLIOGRÁFICO – EMPRÉSTIMO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-061.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PATRIMÔNIO: GESTÃO DE BENS IMÓVEIS');
        $especieProcesso->setTitulo('PATRIMÔNIO: GESTÃO DE BENS IMÓVEIS');
        $especieProcesso->setDescricao('PATRIMÔNIO: GESTÃO DE BENS IMÓVEIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-043.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ABONO PERMANÊNCIA - CONCESSÃO');
        $especieProcesso->setTitulo('PESSOAL: ABONO PERMANÊNCIA - CONCESSÃO');
        $especieProcesso->setDescricao('PESSOAL: ABONO PERMANÊNCIA - CONCESSÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ABONO PERMANÊNCIA - REVISÃO');
        $especieProcesso->setTitulo('PESSOAL: ABONO PERMANÊNCIA - REVISÃO');
        $especieProcesso->setDescricao('PESSOAL: ABONO PERMANÊNCIA - REVISÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL DE FÉRIAS (1/3 CONSTITUCIONAL)');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL DE FÉRIAS (1/3 CONSTITUCIONAL)');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL DE FÉRIAS (1/3 CONSTITUCIONAL)');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.167', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL DE INSALUBRIDADE');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL DE INSALUBRIDADE');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL DE INSALUBRIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.164', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL DE PERICULOSIDADE');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL DE PERICULOSIDADE');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL DE PERICULOSIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.163', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL NOTURNO');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL NOTURNO');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL NOTURNO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.162', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL POR ATIVIDADE PENOSA');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL POR ATIVIDADE PENOSA');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL POR ATIVIDADE PENOSA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.165', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL POR SERVIÇO EXTRAORDINÁRIO');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL POR SERVIÇO EXTRAORDINÁRIO');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL POR SERVIÇO EXTRAORDINÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.166', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ADICIONAL POR TEMPO DE SERVIÇO');
        $especieProcesso->setTitulo('PESSOAL: ADICIONAL POR TEMPO DE SERVIÇO');
        $especieProcesso->setDescricao('PESSOAL: ADICIONAL POR TEMPO DE SERVIÇO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.161', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA ATIVIDADE DESPORTIVA');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA ATIVIDADE DESPORTIVA');
        $especieProcesso->setDescricao('ART. 102, INCISO X, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA CURSO DE FORMAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA CURSO DE FORMAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: AFASTAMENTO PARA CURSO DE FORMAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA DEPOR');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA DEPOR');
        $especieProcesso->setDescricao('ART. 102, INCISO VI, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA EXERCER MANDATO ELETIVO');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA EXERCER MANDATO ELETIVO');
        $especieProcesso->setDescricao('ART. 94 LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA PÓS-GRADUAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA PÓS-GRADUAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: AFASTAMENTO PARA PÓS-GRADUAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA SERVIÇO ELEITORAL (TRE)');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA SERVIÇO ELEITORAL (TRE)');
        $especieProcesso->setDescricao('ART. 102, INCISO VI, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA SERVIR COMO JURADO');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA SERVIR COMO JURADO');
        $especieProcesso->setDescricao('ART. 102, INCISO VI, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AFASTAMENTO PARA SERVIR EM ORGANISMO INTERNACIONAL');
        $especieProcesso->setTitulo('PESSOAL: AFASTAMENTO PARA SERVIR EM ORGANISMO INTERNACIONAL');
        $especieProcesso->setDescricao('ART. 102, INCISO XI, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AJUDA DE CUSTO COM MUDANÇA DE DOMICÍLIO');
        $especieProcesso->setTitulo('PESSOAL: AJUDA DE CUSTO COM MUDANÇA DE DOMICÍLIO');
        $especieProcesso->setDescricao('ART. 53 DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.71', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - CONCESSÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - CONCESSÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - CONCESSÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.51', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - CONCESSÃO – COMPULSÓRIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - CONCESSÃO – COMPULSÓRIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - CONCESSÃO – COMPULSÓRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.52', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - CONCESSÃO - VOLUNTÁRIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - CONCESSÃO - VOLUNTÁRIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - CONCESSÃO - VOLUNTÁRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.53', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - CONCESSÃO – ESPECIAL');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - CONCESSÃO – ESPECIAL');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - CONCESSÃO – ESPECIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.54', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - CONTAGEM TEMPO DE SERVIÇO');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - CONTAGEM TEMPO DE SERVIÇO');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - CONTAGEM TEMPO DE SERVIÇO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.02', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - PENSÃO TEMPORÁRIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - PENSÃO TEMPORÁRIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - PENSÃO TEMPORÁRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.61', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - PENSÃO VITALÍCIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - PENSÃO VITALÍCIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - PENSÃO VITALÍCIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.62', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - REVISÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - REVISÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - REVISÃO - INVALIDEZ PERMANENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.51', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - REVISÃO - COMPULSÓRIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - REVISÃO - COMPULSÓRIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - REVISÃO - COMPULSÓRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.52', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - REVISÃO – VOLUNTÁRIA');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - REVISÃO – VOLUNTÁRIA');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - REVISÃO – VOLUNTÁRIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.53', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APOSENTADORIA - REVISÃO - ESPECIAL');
        $especieProcesso->setTitulo('PESSOAL: APOSENTADORIA - REVISÃO - ESPECIAL');
        $especieProcesso->setDescricao('PESSOAL: APOSENTADORIA - REVISÃO - ESPECIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.54', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: APRESENTAÇÃO DE CERTIFICADO DE CURSO');
        $especieProcesso->setTitulo('PESSOAL: APRESENTAÇÃO DE CERTIFICADO DE CURSO');
        $especieProcesso->setDescricao('PESSOAL: APRESENTAÇÃO DE CERTIFICADO DE CURSO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ASSENTAMENTO FUNCIONAL DO SERVIDOR');
        $especieProcesso->setTitulo('PESSOAL: ASSENTAMENTO FUNCIONAL DO SERVIDOR');
        $especieProcesso->setDescricao('PESSOAL: ASSENTAMENTO FUNCIONAL DO SERVIDOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUSÊNCIA EM RAZÃO DE CASAMENTO');
        $especieProcesso->setTitulo('PESSOAL: AUSÊNCIA EM RAZÃO DE CASAMENTO');
        $especieProcesso->setDescricao('ART. 97, INCISO III, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUSÊNCIA PARA ALISTAMENTO ELEITORAL');
        $especieProcesso->setTitulo('PESSOAL: AUSÊNCIA PARA ALISTAMENTO ELEITORAL');
        $especieProcesso->setDescricao('ART. 97, INCISO II, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUSÊNCIA PARA DOAÇÃO DE SANGUE');
        $especieProcesso->setTitulo('PESSOAL: AUSÊNCIA PARA DOAÇÃO DE SANGUE');
        $especieProcesso->setDescricao('ART. 97, INCISO I, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUSÊNCIA POR FALECIMENTO DE FAMILIAR');
        $especieProcesso->setTitulo('PESSOAL: AUSÊNCIA POR FALECIMENTO DE FAMILIAR');
        $especieProcesso->setDescricao('ART. 97, INCISO III, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO ACIDENTE');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO ACIDENTE');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO ACIDENTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO ALIMENTAÇÃO/REFEIÇÃO');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO ALIMENTAÇÃO/REFEIÇÃO');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO ALIMENTAÇÃO/REFEIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO ASSISTÊNCIA PRÉ-ESCOLAR/CRECHE');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO ASSISTÊNCIA PRÉ-ESCOLAR/CRECHE');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO ASSISTÊNCIA PRÉ-ESCOLAR/CRECHE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO DOENÇA');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO DOENÇA');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO DOENÇA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO FUNERAL');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO FUNERAL');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO FUNERAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO MORADIA');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO MORADIA');
        $especieProcesso->setDescricao('ART. 60-A DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO NATALIDADE');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO NATALIDADE');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO NATALIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO RECLUSÃO');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO RECLUSÃO');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO RECLUSÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.91', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AUXÍLIO-TRANSPORTE');
        $especieProcesso->setTitulo('PESSOAL: AUXÍLIO-TRANSPORTE');
        $especieProcesso->setDescricao('PESSOAL: AUXÍLIO-TRANSPORTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AVALIAÇÃO DE DESEMPENHO INDIVIDUAL');
        $especieProcesso->setTitulo('PESSOAL: AVALIAÇÃO DE DESEMPENHO INDIVIDUAL');
        $especieProcesso->setDescricao('PESSOAL: AVALIAÇÃO DE DESEMPENHO INDIVIDUAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.155', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AVALIAÇÃO DE DESEMPENHO INSTITUCIONAL');
        $especieProcesso->setTitulo('PESSOAL: AVALIAÇÃO DE DESEMPENHO INSTITUCIONAL');
        $especieProcesso->setDescricao('PESSOAL: AVALIAÇÃO DE DESEMPENHO INSTITUCIONAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.32', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AVALIAÇÃO DE ESTÁGIO PROBATÓRIO');
        $especieProcesso->setTitulo('PESSOAL: AVALIAÇÃO DE ESTÁGIO PROBATÓRIO');
        $especieProcesso->setDescricao('PESSOAL: AVALIAÇÃO DE ESTÁGIO PROBATÓRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.7', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: AVERBAÇÃO DE TEMPO DE SERVIÇO');
        $especieProcesso->setTitulo('PESSOAL: AVERBAÇÃO DE TEMPO DE SERVIÇO');
        $especieProcesso->setDescricao('PESSOAL: AVERBAÇÃO DE TEMPO DE SERVIÇO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.02', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: BOLSA DE ESTUDO DE IDIOMA ESTRANGEIRO');
        $especieProcesso->setTitulo('PESSOAL: BOLSA DE ESTUDO DE IDIOMA ESTRANGEIRO');
        $especieProcesso->setDescricao('PESSOAL: BOLSA DE ESTUDO DE IDIOMA ESTRANGEIRO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: BOLSA DE PÓS-GRADUAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: BOLSA DE PÓS-GRADUAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: BOLSA DE PÓS-GRADUAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CADASTRO DE DEPENDENTE NO IMPOSTO DE RENDA');
        $especieProcesso->setTitulo('PESSOAL: CADASTRO DE DEPENDENTE NO IMPOSTO DE RENDA');
        $especieProcesso->setDescricao('PESSOAL: CADASTRO DE DEPENDENTE NO IMPOSTO DE RENDA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.173', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CESSÃO DE SERVIDOR PARA OUTRO ÓRGÃO');
        $especieProcesso->setTitulo('PESSOAL: CESSÃO DE SERVIDOR PARA OUTRO ÓRGÃO');
        $especieProcesso->setDescricao('PESSOAL: CESSÃO DE SERVIDOR PARA OUTRO ÓRGÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CIPA');
        $especieProcesso->setTitulo('PESSOAL: CIPA');
        $especieProcesso->setDescricao('PESSOAL: CIPA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.311', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: COLETA DE IMAGEM DE ASSINATURA');
        $especieProcesso->setTitulo('PESSOAL: COLETA DE IMAGEM DE ASSINATURA');
        $especieProcesso->setDescricao('PESSOAL: COLETA DE IMAGEM DE ASSINATURA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONCURSO PÚBLICO - EXAMES ADMISSIONAIS');
        $especieProcesso->setTitulo('PESSOAL: CONCURSO PÚBLICO - EXAMES ADMISSIONAIS');
        $especieProcesso->setDescricao('PESSOAL: CONCURSO PÚBLICO - EXAMES ADMISSIONAIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-021.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONCURSO PÚBLICO - ORGANIZAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: CONCURSO PÚBLICO - ORGANIZAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: CONCURSO PÚBLICO - ORGANIZAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-021.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONCURSO PÚBLICO - PROVAS E TÍTULOS');
        $especieProcesso->setTitulo('PESSOAL: CONCURSO PÚBLICO - PROVAS E TÍTULOS');
        $especieProcesso->setDescricao('PESSOAL: CONCURSO PÚBLICO - PROVAS E TÍTULOS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-021.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONTROLE DE FREQUÊNCIA/ABONO DE FALTA');
        $especieProcesso->setTitulo('PESSOAL: CONTROLE DE FREQUÊNCIA/ABONO DE FALTA');
        $especieProcesso->setDescricao('PESSOAL: CONTROLE DE FREQUÊNCIA/ABONO DE FALTA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONTROLE DE FREQUÊNCIA/CUMPRIR HORA EXTRA');
        $especieProcesso->setTitulo('PESSOAL: CONTROLE DE FREQUÊNCIA/CUMPRIR HORA EXTRA');
        $especieProcesso->setDescricao('CUMPRIMENTO DE HORAS EXTRAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CONTROLE DE FREQUÊNCIA/FOLHA DE PONTO');
        $especieProcesso->setTitulo('PESSOAL: CONTROLE DE FREQUÊNCIA/FOLHA DE PONTO');
        $especieProcesso->setDescricao('PESSOAL: CONTROLE DE FREQUÊNCIA/FOLHA DE PONTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO DE PÓS-GRADUAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: CURSO DE PÓS-GRADUAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: CURSO DE PÓS-GRADUAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO NO EXTERIOR - COM ÔNUS');
        $especieProcesso->setTitulo('PESSOAL: CURSO NO EXTERIOR - COM ÔNUS');
        $especieProcesso->setDescricao('PESSOAL: CURSO NO EXTERIOR - COM ÔNUS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO NO EXTERIOR - ÔNUS LIMITADO');
        $especieProcesso->setTitulo('PESSOAL: CURSO NO EXTERIOR - ÔNUS LIMITADO');
        $especieProcesso->setDescricao('PESSOAL: CURSO NO EXTERIOR - ÔNUS LIMITADO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO NO EXTERIOR - SEM ÔNUS');
        $especieProcesso->setTitulo('PESSOAL: CURSO NO EXTERIOR - SEM ÔNUS');
        $especieProcesso->setDescricao('PESSOAL: CURSO NO EXTERIOR - SEM ÔNUS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO PROMOVIDO PELA PRÓPRIA INSTITUIÇÃO');
        $especieProcesso->setTitulo('PESSOAL: CURSO PROMOVIDO PELA PRÓPRIA INSTITUIÇÃO');
        $especieProcesso->setDescricao('PESSOAL: CURSO PROMOVIDO PELA PRÓPRIA INSTITUIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: CURSO PROMOVIDO POR OUTRA INSTITUIÇÃO');
        $especieProcesso->setTitulo('PESSOAL: CURSO PROMOVIDO POR OUTRA INSTITUIÇÃO');
        $especieProcesso->setDescricao('PESSOAL: CURSO PROMOVIDO POR OUTRA INSTITUIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DELEGAÇÃO DE COMPETÊNCIA');
        $especieProcesso->setTitulo('PESSOAL: DELEGAÇÃO DE COMPETÊNCIA');
        $especieProcesso->setDescricao('PESSOAL: DELEGAÇÃO DE COMPETÊNCIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DA CONTRIBUIÇÃO PARA O INSS');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DA CONTRIBUIÇÃO PARA O INSS');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DA CONTRIBUIÇÃO PARA O INSS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.172', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DE CONTRIBUIÇÃO ASSOCIATIVA');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DE CONTRIBUIÇÃO ASSOCIATIVA');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DE CONTRIBUIÇÃO ASSOCIATIVA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.171', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DE CONTRIBUIÇÃO SINDICAL');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DE CONTRIBUIÇÃO SINDICAL');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DE CONTRIBUIÇÃO SINDICAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.171', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DE EMPRÉSTIMO CONSIGNADO');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DE EMPRÉSTIMO CONSIGNADO');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DE EMPRÉSTIMO CONSIGNADO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.175', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DE PENSÃO ALIMENTÍCIA');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DE PENSÃO ALIMENTÍCIA');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DE PENSÃO ALIMENTÍCIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.174', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: DESCONTO DO IRPF RETIDO NA FONTE');
        $especieProcesso->setTitulo('PESSOAL: DESCONTO DO IRPF RETIDO NA FONTE');
        $especieProcesso->setDescricao('PESSOAL: DESCONTO DO IRPF RETIDO NA FONTE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.173', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: EMISSÃO DE CERTIDÕES E DECLARAÇÕES');
        $especieProcesso->setTitulo('PESSOAL: EMISSÃO DE CERTIDÕES E DECLARAÇÕES');
        $especieProcesso->setDescricao('PESSOAL: EMISSÃO DE CERTIDÕES E DECLARAÇÕES');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: EMISSÃO DE PROCURAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: EMISSÃO DE PROCURAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: EMISSÃO DE PROCURAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ENCARGO PATRONAL - CONTRIBUIÇÃO PARA INSS');
        $especieProcesso->setTitulo('PESSOAL: ENCARGO PATRONAL - CONTRIBUIÇÃO PARA INSS');
        $especieProcesso->setDescricao('PESSOAL: ENCARGO PATRONAL - CONTRIBUIÇÃO PARA INSS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.184', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ESTÁGIO - DOSSIÊ DO ESTAGIÁRIO');
        $especieProcesso->setTitulo('PESSOAL: ESTÁGIO - DOSSIÊ DO ESTAGIÁRIO');
        $especieProcesso->setDescricao('PESSOAL: ESTÁGIO - DOSSIÊ DO ESTAGIÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.52', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ESTÁGIO - PLANEJAMENTO/ORGANIZAÇÃO GERAL');
        $especieProcesso->setTitulo('PESSOAL: ESTÁGIO - PLANEJAMENTO/ORGANIZAÇÃO GERAL');
        $especieProcesso->setDescricao('PESSOAL: ESTÁGIO - PLANEJAMENTO/ORGANIZAÇÃO GERAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.31', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ESTÁGIO DE SERVIDOR NO BRASIL');
        $especieProcesso->setTitulo('PESSOAL: ESTÁGIO DE SERVIDOR NO BRASIL');
        $especieProcesso->setDescricao('PESSOAL: ESTÁGIO DE SERVIDOR NO BRASIL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ESTÁGIO DE SERVIDOR NO EXTERIOR');
        $especieProcesso->setTitulo('PESSOAL: ESTÁGIO DE SERVIDOR NO EXTERIOR');
        $especieProcesso->setDescricao('PESSOAL: ESTÁGIO DE SERVIDOR NO EXTERIOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: EXONERAÇÃO DE CARGO EFETIVO');
        $especieProcesso->setTitulo('PESSOAL: EXONERAÇÃO DE CARGO EFETIVO');
        $especieProcesso->setDescricao('PESSOAL: EXONERAÇÃO DE CARGO EFETIVO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.7', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FALECIMENTO DE SERVIDOR');
        $especieProcesso->setTitulo('PESSOAL: FALECIMENTO DE SERVIDOR');
        $especieProcesso->setDescricao('PESSOAL: FALECIMENTO DE SERVIDOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.7', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FÉRIAS - ALTERAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: FÉRIAS - ALTERAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: FÉRIAS - ALTERAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FÉRIAS - INTERRUPÇÃO');
        $especieProcesso->setTitulo('PESSOAL: FÉRIAS - INTERRUPÇÃO');
        $especieProcesso->setDescricao('PESSOAL: FÉRIAS - INTERRUPÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FÉRIAS - SOLICITAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: FÉRIAS - SOLICITAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: FÉRIAS - SOLICITAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FICHA FINANCEIRA');
        $especieProcesso->setTitulo('PESSOAL: FICHA FINANCEIRA');
        $especieProcesso->setDescricao('PESSOAL: FICHA FINANCEIRA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: FOLHA DE PAGAMENTO');
        $especieProcesso->setTitulo('PESSOAL: FOLHA DE PAGAMENTO');
        $especieProcesso->setDescricao('PESSOAL: FOLHA DE PAGAMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: GRATIFICAÇÃO DE DESEMPENHO');
        $especieProcesso->setTitulo('PESSOAL: GRATIFICAÇÃO DE DESEMPENHO');
        $especieProcesso->setDescricao('PESSOAL: GRATIFICAÇÃO DE DESEMPENHO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.155', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: GRATIFICAÇÃO NATALINA (DÉCIMO TERCEIRO)');
        $especieProcesso->setTitulo('PESSOAL: GRATIFICAÇÃO NATALINA (DÉCIMO TERCEIRO)');
        $especieProcesso->setDescricao('PESSOAL: GRATIFICAÇÃO NATALINA (DÉCIMO TERCEIRO)');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.154', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: GRATIFICAÇÃO POR ENCARGO - CURSO/CONCURSO');
        $especieProcesso->setTitulo('PESSOAL: GRATIFICAÇÃO POR ENCARGO - CURSO/CONCURSO');
        $especieProcesso->setDescricao('PESSOAL: GRATIFICAÇÃO POR ENCARGO - CURSO/CONCURSO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.156', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO DE EXPEDIENTE - DEFINIÇÃO');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO DE EXPEDIENTE - DEFINIÇÃO');
        $especieProcesso->setDescricao('PESSOAL: HORÁRIO DE EXPEDIENTE - DEFINIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.12', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO DE EXPEDIENTE - ESCALA DE PLANTÃO');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO DE EXPEDIENTE - ESCALA DE PLANTÃO');
        $especieProcesso->setDescricao('PESSOAL: HORÁRIO DE EXPEDIENTE - ESCALA DE PLANTÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.12', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO ESPECIAL - FAMILIAR DEFICIENTE');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO ESPECIAL - FAMILIAR DEFICIENTE');
        $especieProcesso->setDescricao('ART. 98, § 3º, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO ESPECIAL - INSTRUTOR DE CURSO');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO ESPECIAL - INSTRUTOR DE CURSO');
        $especieProcesso->setDescricao('ART. 98, § 4º, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO ESPECIAL - SERVIDOR DEFICIENTE');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO ESPECIAL - SERVIDOR DEFICIENTE');
        $especieProcesso->setDescricao('ART. 98, § 2º, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: HORÁRIO ESPECIAL - SERVIDOR ESTUDANTE');
        $especieProcesso->setTitulo('PESSOAL: HORÁRIO ESPECIAL - SERVIDOR ESTUDANTE');
        $especieProcesso->setDescricao('ART. 98, § 1º, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: INDENIZAÇÃO DE TRANSPORTE (MEIO PRÓPRIO)');
        $especieProcesso->setTitulo('PESSOAL: INDENIZAÇÃO DE TRANSPORTE (MEIO PRÓPRIO)');
        $especieProcesso->setDescricao('ART. 60 DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.72', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA ADOTANTE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA ADOTANTE');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA GESTANTE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA GESTANTE');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA ATIVIDADE POLÍTICA');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA ATIVIDADE POLÍTICA');
        $especieProcesso->setDescricao('ART. 81, INCISO IV, LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA CAPACITAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA CAPACITAÇÃO');
        $especieProcesso->setDescricao('ART. 81, INCISO V, LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA MANDATO CLASSISTA');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA MANDATO CLASSISTA');
        $especieProcesso->setDescricao('ART. 81, INCISO VII, LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA SERVIÇO MILITAR');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA SERVIÇO MILITAR');
        $especieProcesso->setDescricao('ART. 81, INCISO III, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA TRATAMENTO DA PRÓPRIA SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA TRATAMENTO DA PRÓPRIA SAÚDE');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PARA TRATAR DE INTERESSES PARTICULARES');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PARA TRATAR DE INTERESSES PARTICULARES');
        $especieProcesso->setDescricao('ART. 81, INCISO VI, LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PATERNIDADE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PATERNIDADE');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA POR ACIDENTE EM SERVIÇO');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA POR ACIDENTE EM SERVIÇO');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA POR AFASTAMENTO DO CÔNJUGE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA POR AFASTAMENTO DO CÔNJUGE');
        $especieProcesso->setDescricao('ART. 84 DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA POR DOENÇA EM PESSOA DA FAMÍLIA');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA POR DOENÇA EM PESSOA DA FAMÍLIA');
        $especieProcesso->setDescricao('ART. 83 DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA POR DOENÇA PROFISSIONAL');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA POR DOENÇA PROFISSIONAL');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇA PRÊMIO POR ASSIDUIDADE');
        $especieProcesso->setTitulo('PESSOAL: LICENÇA PRÊMIO POR ASSIDUIDADE');
        $especieProcesso->setDescricao('REDAÇÃO ANTERIOR DO ART. 81, INCISO V, LEI Nº 8.112/1990. EM RAZÃO DE POSSÍVEL DIREITO ADQUIRIDO, MUITOS SERVIDORES AINDA USUFRUEM ESTE TIPO DE LICENÇA.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: LICENÇAS POR ABORTO/NATIMORTO');
        $especieProcesso->setTitulo('PESSOAL: LICENÇAS POR ABORTO/NATIMORTO');
        $especieProcesso->setDescricao('ART. 102, INCISO VIII, C/C ART. 207, § 3º E § 4º, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: MOVIMENTAÇÃO DE SERVIDOR');
        $especieProcesso->setTitulo('PESSOAL: MOVIMENTAÇÃO DE SERVIDOR');
        $especieProcesso->setDescricao('PESSOAL: MOVIMENTAÇÃO DE SERVIDOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: MOVIMENTO REIVINDICATÓRIO');
        $especieProcesso->setTitulo('PESSOAL: MOVIMENTO REIVINDICATÓRIO');
        $especieProcesso->setDescricao('PESSOAL: MOVIMENTO REIVINDICATÓRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.032', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: NEGOCIAÇÃO SINDICAL E ACORDO COLETIVO');
        $especieProcesso->setTitulo('PESSOAL: NEGOCIAÇÃO SINDICAL E ACORDO COLETIVO');
        $especieProcesso->setDescricao('PESSOAL: NEGOCIAÇÃO SINDICAL E ACORDO COLETIVO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.031', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO - PROMOÇÃO DE CURSOS PELO ÓRGÃO E ENTIDADE');
        $especieProcesso->setTitulo('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO - PROMOÇÃO DE CURSOS PELO ÓRGÃO E ENTIDADE');
        $especieProcesso->setDescricao('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO - PROMOÇÃO DE CURSOS PELO ÓRGÃO E ENTIDADE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO – SUBSTITUIÇÃO');
        $especieProcesso->setTitulo('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO – SUBSTITUIÇÃO');
        $especieProcesso->setDescricao('PESSOAL: NOMEAÇÃO/EXONERAÇÃO DE CARGO COMISSIONADO E DESIGNAÇÃO/DISPENSA DE SUBSTITUTO – SUBSTITUIÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.5', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setTitulo('PESSOAL: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setDescricao('PESSOAL: NORMATIZAÇÃO INTERNA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-050.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: OCUPAÇÃO DE IMÓVEL FUNCIONAL');
        $especieProcesso->setTitulo('PESSOAL: OCUPAÇÃO DE IMÓVEL FUNCIONAL');
        $especieProcesso->setDescricao('PESSOAL: OCUPAÇÃO DE IMÓVEL FUNCIONAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.92', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: ORIENTAÇÕES E DIRETRIZES GERAIS');
        $especieProcesso->setTitulo('PESSOAL: ORIENTAÇÕES E DIRETRIZES GERAIS');
        $especieProcesso->setDescricao('PESSOAL: ORIENTAÇÕES E DIRETRIZES GERAIS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-010.01', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PAGAMENTO DE PROVENTO');
        $especieProcesso->setTitulo('PESSOAL: PAGAMENTO DE PROVENTO');
        $especieProcesso->setDescricao('PESSOAL: PAGAMENTO DE PROVENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PAGAMENTO DE REMUNERAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: PAGAMENTO DE REMUNERAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: PAGAMENTO DE REMUNERAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE ADVERTÊNCIA');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE ADVERTÊNCIA');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE CASSAÇÃO DE APOSENTADORIA');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE CASSAÇÃO DE APOSENTADORIA');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE DEMISSÃO DE CARGO EFETIVO');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE DEMISSÃO DE CARGO EFETIVO');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE DESTITUIÇÃO CARGO EM COMISSÃO');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE DESTITUIÇÃO CARGO EM COMISSÃO');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE DISPONIBILIDADE');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE DISPONIBILIDADE');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENALIDADE SUSPENSÃO');
        $especieProcesso->setTitulo('PESSOAL: PENALIDADE SUSPENSÃO');
        $especieProcesso->setDescricao('APLICAÇÃO DE PENALIDADE. O REGISTRO DAS PENALIDADES DISCIPLINARES DEVERÁ SER FEITO NA PASTA DE ASSENTAMENTO INDIVIDUAL DO SERVIDOR.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-027.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PENSÃO POR MORTE DE SERVIDOR');
        $especieProcesso->setTitulo('PESSOAL: PENSÃO POR MORTE DE SERVIDOR');
        $especieProcesso->setDescricao('PESSOAL: PENSÃO POR MORTE DE SERVIDOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.61', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PLANEJAMENTO DA FORÇA DE TRABALHO');
        $especieProcesso->setTitulo('PESSOAL: PLANEJAMENTO DA FORÇA DE TRABALHO');
        $especieProcesso->setDescricao('PESSOAL: PLANEJAMENTO DA FORÇA DE TRABALHO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.021', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PLANO DE CAPACITAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: PLANO DE CAPACITAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: PLANO DE CAPACITAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PRÊMIOS DE RECONHECIMENTO');
        $especieProcesso->setTitulo('PESSOAL: PRÊMIOS DE RECONHECIMENTO');
        $especieProcesso->setDescricao('PESSOAL: PRÊMIOS DE RECONHECIMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-029.3', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PREVENÇÃO DE ACIDENTES NO TRABALHO');
        $especieProcesso->setTitulo('PESSOAL: PREVENÇÃO DE ACIDENTES NO TRABALHO');
        $especieProcesso->setDescricao('PESSOAL: PREVENÇÃO DE ACIDENTES NO TRABALHO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.32', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO EFETIVO)');
        $especieProcesso->setTitulo('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO EFETIVO)');
        $especieProcesso->setDescricao('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO EFETIVO)');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.63', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO ESPECÍFICO)');
        $especieProcesso->setTitulo('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO ESPECÍFICO)');
        $especieProcesso->setDescricao('PESSOAL: PROGRESSÃO E PROMOÇÃO (QUADRO ESPECÍFICO)');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.63', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EFETIVO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EFETIVO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EFETIVO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EM COMISSÃO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EM COMISSÃO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - NOMEAÇÃO PARA CARGO EM COMISSÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - POR APROVEITAMENTO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - POR APROVEITAMENTO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - POR APROVEITAMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - POR READAPTAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - POR READAPTAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - POR READAPTAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - POR RECONDUÇÃO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - POR RECONDUÇÃO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - POR RECONDUÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - POR REINTEGRAÇÃO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - POR REINTEGRAÇÃO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - POR REINTEGRAÇÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: PROVIMENTO - POR REVERSÃO');
        $especieProcesso->setTitulo('PESSOAL: PROVIMENTO - POR REVERSÃO');
        $especieProcesso->setDescricao('PESSOAL: PROVIMENTO - POR REVERSÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: RELAÇÃO COM CONSELHO PROFISSIONAL');
        $especieProcesso->setTitulo('PESSOAL: RELAÇÃO COM CONSELHO PROFISSIONAL');
        $especieProcesso->setDescricao('PESSOAL: RELAÇÃO COM CONSELHO PROFISSIONAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.033', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO A PEDIDO - CONCURSO INTERNO');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO A PEDIDO - CONCURSO INTERNO');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO III, ALÍNEA "C", DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO A PEDIDO COM MUDANÇA DE SEDE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO A PEDIDO COM MUDANÇA DE SEDE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO II, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO A PEDIDO PARA ACOMPANHAR CÔNJUGE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO A PEDIDO PARA ACOMPANHAR CÔNJUGE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO III, ALÍNEA "A", DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO A PEDIDO POR MOTIVO DE SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO A PEDIDO POR MOTIVO DE SAÚDE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO III, ALÍNEA "B", DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO A PEDIDO SEM MUDANÇA DE SEDE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO A PEDIDO SEM MUDANÇA DE SEDE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO II, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO DE OFÍCIO COM MUDANÇA DE SEDE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO DE OFÍCIO COM MUDANÇA DE SEDE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO I, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REMOÇÃO DE OFÍCIO SEM MUDANÇA DE SEDE');
        $especieProcesso->setTitulo('PESSOAL: REMOÇÃO DE OFÍCIO SEM MUDANÇA DE SEDE');
        $especieProcesso->setDescricao('ART. 36, PARÁGRAFO ÚNICO, INCISO I, DA LEI Nº 8.112/1990.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REQUISIÇÃO DE SERVIDOR EXTERNO');
        $especieProcesso->setTitulo('PESSOAL: REQUISIÇÃO DE SERVIDOR EXTERNO');
        $especieProcesso->setDescricao('PESSOAL: REQUISIÇÃO DE SERVIDOR EXTERNO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: REQUISIÇÃO DE SERVIDOR INTERNO');
        $especieProcesso->setTitulo('PESSOAL: REQUISIÇÃO DE SERVIDOR INTERNO');
        $especieProcesso->setDescricao('PESSOAL: REQUISIÇÃO DE SERVIDOR INTERNO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setTitulo('PESSOAL: RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setDescricao('PESSOAL: RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: RESTRUTURAÇÃO DE CARGOS E FUNÇÕES');
        $especieProcesso->setTitulo('PESSOAL: RESTRUTURAÇÃO DE CARGOS E FUNÇÕES');
        $especieProcesso->setDescricao('PESSOAL: RESTRUTURAÇÃO DE CARGOS E FUNÇÕES');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-020.022', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: RETRIBUIÇÃO POR CARGO EM COMISSÃO');
        $especieProcesso->setTitulo('PESSOAL: RETRIBUIÇÃO POR CARGO EM COMISSÃO');
        $especieProcesso->setDescricao('PESSOAL: RETRIBUIÇÃO POR CARGO EM COMISSÃO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.153', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SALÁRIO-FAMÍLIA');
        $especieProcesso->setTitulo('PESSOAL: SALÁRIO-FAMÍLIA');
        $especieProcesso->setDescricao('PESSOAL: SALÁRIO-FAMÍLIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-026.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - ATESTADO DE COMPARECIMENTO');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - ATESTADO DE COMPARECIMENTO');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - ATESTADO DE COMPARECIMENTO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.14', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - AUXÍLIO-SAÚDE GEAP');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - AUXÍLIO-SAÚDE GEAP');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - AUXÍLIO-SAÚDE GEAP');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - CADASTRO DE DEPENDENTE ESTUDANTE NO AUXÍLIO-SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - CADASTRO DE DEPENDENTE ESTUDANTE NO AUXÍLIO-SAÚDE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - CADASTRO DE DEPENDENTE ESTUDANTE NO AUXÍLIO-SAÚDE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - EXCLUSÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - EXCLUSÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - EXCLUSÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - INSPEÇÃO PERIÓDICA');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - INSPEÇÃO PERIÓDICA');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - INSPEÇÃO PERIÓDICA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.14', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - LANÇAMENTO MENSAL DO AUXÍLIO-SAÚDE NO SIAPE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - LANÇAMENTO MENSAL DO AUXÍLIO-SAÚDE NO SIAPE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - LANÇAMENTO MENSAL DO AUXÍLIO-SAÚDE NO SIAPE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - PAGAMENTO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - PAGAMENTO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - PAGAMENTO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - PAGAMENTO DE RETROATIVO');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - PAGAMENTO DE RETROATIVO');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - PAGAMENTO DE RETROATIVO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - PLANO DE SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - PLANO DE SAÚDE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - PLANO DE SAÚDE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - PRONTUÁRIO MÉDICO');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - PRONTUÁRIO MÉDICO');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - PRONTUÁRIO MÉDICO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.14', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - RESSARCIMENTO AO ERÁRIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-059.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE - SOLICITAÇÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE - SOLICITAÇÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE - SOLICITAÇÃO DE AUXÍLIO-SAÚDE');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-023.6', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SAÚDE E QUALIDADE DE VIDA NO TRABALHO');
        $especieProcesso->setTitulo('PESSOAL: SAÚDE E QUALIDADE DE VIDA NO TRABALHO');
        $especieProcesso->setDescricao('PESSOAL: SAÚDE E QUALIDADE DE VIDA NO TRABALHO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-025.14', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setTitulo('PESSOAL: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setDescricao('PESSOAL: SUBSIDIAR AÇÃO JUDICIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-004.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PESSOAL: VACÂNCIA - POSSE EM CARGO INACUMULÁVEL');
        $especieProcesso->setTitulo('PESSOAL: VACÂNCIA - POSSE EM CARGO INACUMULÁVEL');
        $especieProcesso->setDescricao('PESSOAL: VACÂNCIA - POSSE EM CARGO INACUMULÁVEL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-022.7', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: ACOMPANHAMENTO DO PLANO OPERACIONAL');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: ACOMPANHAMENTO DO PLANO OPERACIONAL');
        $especieProcesso->setDescricao('PLANEJAMENTO ESTRATÉGICO: ACOMPANHAMENTO DO PLANO OPERACIONAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO ESTRATÉGICO');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO ESTRATÉGICO');
        $especieProcesso->setDescricao('ELABORAÇÃO DAS PROPOSTAS DO PLANO ESTRATÉGICO DO ÓRGÃO.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: ELABORAÇÃO DO PLANO OPERACIONAL');
        $especieProcesso->setDescricao('CONSOLIDAÇÃO DO PLANO OPERACIONAL DO ÓRGÃO.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: GESTÃO DE RISCO');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: GESTÃO DE RISCO');
        $especieProcesso->setDescricao('GESTÃO DOS RISCOS E CONTROLE DE RISCOS COM VISTA AO ALCANCE DOS OBJETIVOS ESTRATÉGICOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: GESTÃO DO PLANO ESTRATÉGICO');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: GESTÃO DO PLANO ESTRATÉGICO');
        $especieProcesso->setDescricao('GESTÃO DO PLANO ESTRATÉGICO DO ÓRGÃO.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('PLANEJAMENTO ESTRATÉGICO: INTELIGÊNCIA ESTRATÉGICA');
        $especieProcesso->setTitulo('PLANEJAMENTO ESTRATÉGICO: INTELIGÊNCIA ESTRATÉGICA');
        $especieProcesso->setDescricao('MONITORAMENTO DOS OBJETIVOS ESTRATÉGICOS, CENÁRIOS PROSPECTIVOS E ESTRATÉGIAS DOS ATORES QUE IMPACTAM NO ALCANCE DO OBJETIVOS ESTRATÉGICOS.');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-015.1', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - COM ÔNUS');
        $especieProcesso->setTitulo('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - COM ÔNUS');
        $especieProcesso->setDescricao('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - COM ÔNUS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - ÔNUS LIMITADO');
        $especieProcesso->setTitulo('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - ÔNUS LIMITADO');
        $especieProcesso->setDescricao('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - ÔNUS LIMITADO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.22', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - SEM ÔNUS');
        $especieProcesso->setTitulo('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - SEM ÔNUS');
        $especieProcesso->setDescricao('RELAÇÕES INTERNACIONAIS: COMPOSIÇÃO DE DELEGAÇÃO - SEM ÔNUS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.23', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('RELAÇÕES INTERNACIONAIS: COOPERAÇÃO INTERNACIONAL');
        $especieProcesso->setTitulo('RELAÇÕES INTERNACIONAIS: COOPERAÇÃO INTERNACIONAL');
        $especieProcesso->setDescricao('RELAÇÕES INTERNACIONAIS: COOPERAÇÃO INTERNACIONAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-011', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: AUTOMAÇÃO E CONTROLE PREDIAL');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: AUTOMAÇÃO E CONTROLE PREDIAL');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: AUTOMAÇÃO E CONTROLE PREDIAL');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-046.2', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/GARAGEM');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/GARAGEM');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/GARAGEM');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-046.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/PORTARIA');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/PORTARIA');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: CONTROLE DE ACESSO/PORTARIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-046.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: PREVENÇÃO CONTRA INCÊNDIO');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: PREVENÇÃO CONTRA INCÊNDIO');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: PREVENÇÃO CONTRA INCÊNDIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-046.13', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: PROJETO CONTRA INCÊNDIO');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: PROJETO CONTRA INCÊNDIO');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: PROJETO CONTRA INCÊNDIO');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-046.12', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SEGURANÇA INSTITUCIONAL: SERVIÇO DE VIGILÂNCIA');
        $especieProcesso->setTitulo('SEGURANÇA INSTITUCIONAL: SERVIÇO DE VIGILÂNCIA');
        $especieProcesso->setDescricao('SEGURANÇA INSTITUCIONAL: SERVIÇO DE VIGILÂNCIA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-045.4', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SUPRIMENTO DE FUNDOS: CONCESSÃO E PRESTAÇÃO DE CONTAS');
        $especieProcesso->setTitulo('SUPRIMENTO DE FUNDOS: CONCESSÃO E PRESTAÇÃO DE CONTAS');
        $especieProcesso->setDescricao('SUPRIMENTO DE FUNDOS: CONCESSÃO E PRESTAÇÃO DE CONTAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.221', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('SUPRIMENTO DE FUNDOS: SOLICITAÇÃO DE DESPESA');
        $especieProcesso->setTitulo('SUPRIMENTO DE FUNDOS: SOLICITAÇÃO DE DESPESA');
        $especieProcesso->setDescricao('SUPRIMENTO DE FUNDOS: SOLICITAÇÃO DE DESPESA');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-052.221', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('VIAGEM: EXTERIOR - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setTitulo('VIAGEM: EXTERIOR - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setDescricao('VIAGEM: EXTERIOR - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('VIAGEM: NO PAÍS - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setTitulo('VIAGEM: NO PAÍS - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setDescricao('VIAGEM: NO PAÍS - PRESTAÇÃO DE CONTAS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO PAÍS');
        $especieProcesso->setTitulo('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO PAÍS');
        $especieProcesso->setDescricao('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO PAÍS');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.11', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);

        $especieProcesso = new EspecieProcesso();
        $especieProcesso->setNome('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO EXTERIOR');
        $especieProcesso->setTitulo('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO EXTERIOR');
        $especieProcesso->setDescricao('VIAGEM: PUBLICAÇÃO DE BOLETIM - NO EXTERIOR');
        $especieProcesso->setGeneroProcesso($this->getReference('GeneroProcesso-ADMINISTRATIVO', GeneroProcesso::class));
        $especieProcesso->setModalidadeMeio($this->getReference('ModalidadeMeio-ELETRÔNICO', ModalidadeMeio::class));

        $especieProcesso->setClassificacao($this->getReference('Classificacao-028.21', Classificacao::class));
        $manager->persist($especieProcesso);

        $this->addReference('EspecieProcesso-'.$especieProcesso->getNome(), $especieProcesso);
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
        return 2;
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
