<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadClassificacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDestinacao;

/**
 * Class LoadClassificacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadClassificacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO GERAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('000');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao1', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MODERNIZAÇÃO E REFORMA ADMINISTRATIVA PROJETOS, ESTUDOS E NORMAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('001');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao2', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANOS, PROGRAMAS E PROJETOS DE TRABALHO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('002');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao3', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RELATÓRIOS DE ATIVIDADES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('003');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao4', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ACORDOS. AJUSTES. CONTRATOS. CONVÊNIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('004');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao5', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ORGANIZAÇÃO E FUNCIONAMENTO: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('010');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao6', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO NOS ÓRGÃOS COMPETENTES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('010.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao7', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REGIMENTOS. REGULAMENTOS. ESTATUTOS. ORGANOGRAMAS. ESTRUTURAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('010.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao8', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUDIÊNCIAS. DESPACHOS. REUNIÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('010.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao9', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMISSÕES. CONSELHOS. GRUPOS DE TRABALHO. JUNTAS. COMITÊS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('011');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao10', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ATOS DE CRIAÇÃO, ATAS, RELATÓRIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('011.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao11', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICAÇÃO SOCIAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('012');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao12', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÕES COM A IMPRENSA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('012.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao13', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CREDENCIAMENTO DE JORNALISTAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('012.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao14', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ENTREVISTAS. NOTICIÁRIOS. REPORTAGENS. EDITORIAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('012.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao15', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DIVULGAÇÃO INTERNA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('012.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao16', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CAMPANHAS INSTITUCIONAIS. PUBLICIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('012.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao17', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES À ORGANIZAÇÃO E FUNCIONAMENTO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('019');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao18', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INFORMAÇÕES SOBRE O ÓRGÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('019.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao19', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PESSOAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('020');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao20', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LEGISLAÇÃO NORMAS, REGULAMENTAÇÕES, DIRETRIZES, ESTATUTOS, REGULAMENTOS, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao21', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('BOLETINS ADMINISTRATIVO, DE PESSOAL E DE SERVIÇO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(10);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao22', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('IDENTIFICAÇÃO FUNCIONAL (INCLUSIVE CARTEIRA, CARTÃO, CRACHÁ, CREDENCIAL E PASSAPORTE DIPLOMÁTICO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('Enquanto o servidor permanecer');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao23', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OBRIGAÇÕES TRABALHISTAS E ESTATUTÁRIAS. RELAÇÕES COM ÓRGÃOS NORMATIZADORES DA ADMINISTRAÇÃO PÚBLICA. LEI DOS 2/3. RAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao24', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÕES COM OS CONSELHOS PROFISSIONAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao25', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SINDICATOS. ACORDOS. DISSÍDIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao26', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSENTAMENTOS INDIVIDUAIS. CADASTRO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('020.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('100 Anos. Ver detalhes no campo observação');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('Enquanto o servidor permanecer');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao27', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECRUTAMENTO E SELEÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('021');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao28', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CANDIDATOS A CARGO E EMPREGO PÚBLICOS: INSCRIÇÃO E CURRICULUM VITAE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('021.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao29', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EXAMES DE SELEÇÃO (CONCURSOS PÚBLICOS) PROVAS E TÍTULOS, TESTES PSICOTÉCNICOS E EXAMES MÉDICOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('021.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(6);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao30', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTITUIÇÃO DE BANCAS EXAMINADORAS, EDITAIS, EXEMPLARES ÚNICOS DE PROVAS, GABARITOS, RESULTADOS E RECURSOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('021.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(6);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao31', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('APERFEIÇOAMENTO E TREINAMENTO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('022');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao32', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CURSOS (INCLUSIVE BOLSAS DE ESTUDO)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('022.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao33', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOVIDOS PELA INSTITUIÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao34', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROPOSTAS, ESTUDOS, EDITAIS, PROGRAMAS, RELATÓRIOS FINAIS, EXEMPLARES ÚNICOS DE EXERCÍCIOS, RELAÇÃO DE PARTICIPANTES, AVALIAÇÃO E CONTROLE DE EXPEDIÇÃO DE CERTIFICADOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.111');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao35', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOVIDOS POR OUTRAS INSTITUIÇÕES');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('022.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao36', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO BRASIL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.121');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao37', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO EXTERIOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.122');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao38', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOVIDOS PELA INSTITUIÇÃO (INCLUSIVE PROPOSTAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao39', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ESTUDOS, PROPOSTAS, PROGRAMAS, RELATÓRIOS FINAIS, RELAÇÃO DE PARTICIPANTES, AVALIAÇÃO E DECLARAÇÃO DE COMPROVAÇÃO DE ESTÁGIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.211');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao40', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOVIDOS POR OUTRAS INSTITUIÇÕES');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('022.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao41', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO BRASIL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.221');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao42', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO EXTERIOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('022.222');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao43', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A APERFEIÇOAMENTO E TREINAMENTO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('022.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao44', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('QUADROS, TABELAS E POLÍTICA DE PESSOAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('023');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao45', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ESTUDOS E PREVISÃO DE PESSOAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao46', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO, CLASSIFICAÇÃO, TRANSFORMAÇÃO, TRANSPOSIÇÃO E REMUNERAÇÃO DE CARGOS E FUNÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao47', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REESTRUTURAÇÕES E ALTERAÇÕES SALARIAIS. ASCENSÃO E PROGRESSÃO FUNCIONAL AVALIAÇÃO DE DESEMPENHO. ENQUADRAMENTO. EQUIPARAÇÃO, REAJUSTE E REPOSIÇÃO SALARIAL PROMOÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.03');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao48', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTAÇÃO DE PESSOAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('023.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao49', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ADMISSÃO. APROVEITAMENTO. CONTRATAÇÃO. NOMEAÇÃO. READMISSÃO. READAPTAÇÃO. RECONDUÇÃO. REINTEGRAÇÃO. REVERSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao50', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DEMISSÃO. DISPENSA. EXONERAÇÃO. RESCISÃO CONTRATUAL. FALECIMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao51', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LOTAÇÃO. REMOÇÃO. TRANSFERÊNCIA. PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao52', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESIGNAÇÃO. DISPONIBILIDADE. REDISTRIBUIÇÃO. SUBSTITUIÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao53', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REQUISIÇÃO. CESSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('023.15');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao54', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DIREITOS, OBRIGAÇÕES E VANTAGENS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao55', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FOLHAS DE PAGAMENTO. FICHAS FINANCEIRAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao56', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SALÁRIOS, VENCIMENTOS, PROVENTOS E REMUNERAÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao57', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SALÁRIO-FAMÍLIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.111');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(19);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao58', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ABONO OU PROVENTO PROVISÓRIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.112');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao59', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ABONO DE PERMANÊNCIA EM SERVIÇO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.113');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DA APOSENTADORIA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao60', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS SALÁRIOS, VENCIMENTOS, PROVENTOS E REMUNERAÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.119');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao61', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('GRATIFICAÇÕES (INCLUSIVE INCORPORAÇÕES)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao62', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DE FUNÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.121');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao63', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DE JETONS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.122');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao64', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CARGOS EM COMISSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.123');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao65', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NATALINAS (DÉCIMO TERCEIRO SALÁRIO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.124');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao66', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS GRATIFICAÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.129');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao67', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ADICIONAIS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao68', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TEMPO DE SERVIÇO (ANUÊNIOS, BIÊNIOS E QUINQUÊNIOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.131');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao69', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NOTURNO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.132');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao70', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PERICULOSIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.133');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao71', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSALUBRIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.134');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao72', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ATIVIDADES PENOSAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.135');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao73', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS EXTRAORDINÁRIOS (HORAS EXTRAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.136');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao74', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FÉRIAS: ADICIONAL DE 1/3 E ABONO PECUNIÁRIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.137');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao75', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ADICIONAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.139');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao76', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESCONTOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao77', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO SINDICAL DO SERVIDOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.141');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao78', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO PARA O PLANO DE SEGURIDADE SOCIAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.142');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao79', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('IMPOSTO DE RENDA RETIDO NA FONTE (IRRF)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.143');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao80', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÕES ALIMENTÍCIAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.144');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao81', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSIGNAÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.145');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao82', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS DESCONTOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.149');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao83', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ENCARGOS PATRONAIS. RECOLHIMENTOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.15');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao84', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMA DE FORMAÇÃO DO PATRIMÔNIO DO SERVIDOR PÚBLICO (PASEP). PROGRAMA DE INTEGRAÇÃO SOCIAL (PIS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.151');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao85', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FUNDO DE GARANTIA POR TEMPO DE SERVIÇO (FGTS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.152');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao86', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO SINDICAL DO EMPREGADOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.153');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao87', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO PARA O PLANO DE SEGURIDADE SOCIAL (INCLUSIVE CONTRIBUIÇÕES ANTERIORES)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.154');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao88', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SALÁRIO MATERNIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.155');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao89', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('IMPOSTO DE RENDA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.156');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao90', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FÉRIAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao91', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LICENÇAS: ACIDENTE EM SERVIÇO. ADOTANTE. AFAST DO CÔNJUGE/COMPANHEIRO. ATIV POLÍTICA. CAPACITAÇÃO PROF. DESEMP DE MANDATO CLASSISTA. DOENÇA EM PESSOA DA FAMÍLIA. GESTANTE. PATERNIDADE. PRÊMIO POR ASSID SVÇO MILITAR. TRAT DE INTER PART.');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao92', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AFASTAMENTOS: PARA DEPOR. PARA EXERCER MANDATO ELETIVO. PARA SERVIR AO TRIBUNAL REGIONAL ELEITORAL (TRE). PARA SERVIR COMO JURADO. SUSPENSÃO DE CONTRATO DE TRABALHO (CLT)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao93', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REEMBOLSO DE DESPESAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao94', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MUDANÇA DE DOMICÍLIO DE SERVIDORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao95', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LOCOMOÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao96', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS REEMBOLSOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.59');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao97', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS DIREITOS, OBRIGAÇÕES E VANTAGENS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('024.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao98', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÕES: ALISTAMENTO ELEITORAL. CASAMENTO (GALA). DOAÇÃO DE SANGUE. FALECIMENTO DE FAMILIARES (NOJO). HORÁRIO ESPECIAL PARA SERVIDOR ESTUDANTE. HORÁRIO ESPECIAL PARA SERVIDOR PORTADOR DE DEFICIÊNCIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.91');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao99', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUXÍLIOS: ALIMENTAÇÃO/REFEIÇÃO. ASSISTÊNCIA PRÉ-ESCOLAR/CRECHE. FARDAMENTO/UNIFORME. MORADIA. VALE-TRANSPORTE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('024.92');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao100', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('APURAÇÃO DE RESPONSABILIDADE E AÇÃO DISCIPLINAR');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('025');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao101', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DENÚNCIAS. SINDICÂNCIAS. INQUÉRITOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('025.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao102', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROCESSOS DISCIPLINARES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('025.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao103', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PPENALIDADES DISCIPLINARES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('025.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao104', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PREVIDÊNCIA, ASSISTÊNCIA E SEGURIDADE SOCIAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('026');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao105', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PREVIDÊNCIA PRIVADA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao106', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('BENEFÍCIOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('026.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao107', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SEGUROS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao108', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUXÍLIOS: ACIDENTE. DOENÇA. FUNERAL. NATALIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao109', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECLUSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.121');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao110', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('APOSENTADORIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao111', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTAGEM E AVERBAÇÃO DE TEMPO DE SERVIÇO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.131');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DA APOSENTADORIA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao112', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÕES: PROVISÓRIA E TEMPORÁRIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.132');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao113', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÃO VITALÍCIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.1321');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao114', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS BENEFÍCIOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('026.19');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao115', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ADIANTAMENTOS E EMPRÉSTIMOS A SERVIDORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.191');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao116', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSISTÊNCIA À SAÚDE (INCLUSIVE PLANOS DE SAÚDE)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.192');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao117', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PRONTUÁRIO MÉDICO DO SERVIDOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.1921');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao118', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO DE IMÓVEIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.193');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao119', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OCUPAÇÃO DE PRÓPRIOS DA UNIÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.194');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO PERMANECE A OCUPAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao120', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TRANSPORTES PARA SERVIDORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.195');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao121', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('HIGIENE E SEGURANÇA DO TRABALHO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao122', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PREVENÇÃO DE ACIDENTES DE TRABALHO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao123', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMISSÃO INTERNA DE PREVENÇÃO DE ACIDENTES (CIPA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.211');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao124', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO, DESIGNAÇÃO, PROPOSTAS, RELATÓRIOS E ATAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.212');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(3);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao125', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REFEITÓRIOS, CANTINAS E COPAS (FORNECIMENTO DE REFEIÇÕES)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao126', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSPEÇÕES PERIÓDICAS DE SAÚDE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('026.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao127', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A PESSOAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('029');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao128', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('HORÁRIO DE EXPEDIENTE (INCLUSIVE ESCALA DE PLANTÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao129', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE FREQUÊNCIA: LIVROS, CARTÕES, FOLHAS DE PONTO, ABONO DE FALTAS, CUMPRIMENTO DE HORAS EXTRAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(47);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao130', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MISSÕES FORA DA SEDE. VIAGENS A SERVIÇO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('029.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao131', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO PAÍS: AJUDAS DE CUSTO, DIÁRIAS, PASSAGENS (INCLUSIVE DEVOLUÇÃO), PRESTAÇÕES DE CONTAS, RELATÓRIOS DE VIAGEM');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao132', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NO EXTERIOR (AFASTAMENTO DO PAÍS)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('029.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao133', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SEM ÔNUS PARA A INSTITUIÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.221');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao134', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COM ÔNUS PARA A INSTITUIÇÃO. AUTOR. DE AFASTAMENTO. DIÁRIAS (INC. COMPRA DE MOEDA ESTRANGEIRA). LISTA DE PARTICIPANTES (COMITIVAS E DELEGAÇÕES). PASSAGENS. PASSAPORTES. PRESTAÇÕES DE CONTAS. RELATÓRIOS DE VIAGEM. RESERVAS DE HOTEL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.222');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao135', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INCENTIVOS FUNCIONAIS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('029.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao136', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PRÊMIOS: CONCESSÃO DE MEDALHAS, DIPLOMAS DE HONRA AO MÉRITO E ELOGIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao137', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DELEGAÇÕES DE COMPETÊNCIA. PROCURAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao138', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS PROFISSIONAIS TRANSITÓRIOS: AUTÔNOMOS E COLABORADORES (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('Ver observações');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA A PRESTAÇÃO DO SERVIÇO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao139', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AÇÕES TRABALHISTAS. RECLAMAÇÕES TRABALHISTAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ O TRÂNSITO EM JULGADO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao140', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTOS REIVINDICATÓRIOS: GREVES E PARALISAÇÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('029.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao141', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('030');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao142', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CADASTRO DE FORNECEDORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('030.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao143', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ESPECIFICAÇÃO. PADRONIZAÇÃO. CODIFICAÇÃO. PREVISÃO. CATÁLOGO. IDENTIFICAÇÃO. CLASSIFICAÇÃO (INCLUSIVE AMOSTRAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('031');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao144', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REQUISIÇÃO E CONTROLE DE SERVIÇOS REPROGRÁFICOS (INCLUSIVE ASSINATURAS AUTORIZADAS E REPRODUÇÕES DE FORMULÁRIOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('032');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao145', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('033');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao146', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('033.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao147', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA (INCLUSIVE COMPRA POR IMPORTAÇÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao148', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ALUGUEL. COMODATO. LEASING');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao149', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EMPRÉSTIMO. CESSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao150', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO. PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao151', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('033.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao152', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao153', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO. DOAÇÃO. PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao154', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONFECÇÃO DE IMPRESSOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('033.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao155', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTAÇÃO DE MATERIAL (PERMANENTE E DE CONSUMO)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('034');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao156', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TERMOS DE RESPONSABILIDADE (INCLUSIVE RMB OU RMBM)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao157', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE ESTOQUE (INCLUSIVE REQUISIÇÃO, DISTRIBUIÇÃO E RMA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao158', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EXTRAVIO. ROUBO. DESAPARECIMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('Até a conclusão do caso');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao159', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TRANSPORTE DE MATERIAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao160', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUTORIZAÇÃO DE SAÍDA DE MATERIAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao161', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECOLHIMENTO DE MATERIAL AO DEPÓSITO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('034.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao162', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ALIENAÇÃO. BAIXA (MATERIAL PERMANENTE E DE CONSUMO)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('035');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao163', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VENDA (INCLUSIVE LEILÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('035.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao164', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO. DOAÇÃO. PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('035.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao165', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO E MANUTENÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('036');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao166', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REQUISIÇÃO E CONTRATAÇÃO DE SERVIÇOS (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('036.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao167', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS EXECUTADOS EM OFICINAS DO ÓRGÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('036.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao168', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('037');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao169', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('037.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao170', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('037.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao171', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A MATERIAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('039');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao172', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PATRIMÔNIO: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDI- MENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('040');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao173', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS: PROJETOS, PLANTAS E ESCRITURAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(3);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao174', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FORNECIMENTO E MANUTENÇÃO DE SERVIÇOS BÁSICOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao175', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ÁGUA E ESGOTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.011');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao176', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('GÁS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.012');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao177', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LUZ E FORÇA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.013');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao178', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMISSÃO INTERNA DE CONSERVAÇÃO DE ENERGIA (CICE)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao179', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO, DESIGNAÇÃO, PROPOSTAS DE REDUÇÃO DE GASTOS COM ENERGIA, RELATÓRIOS E ATAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.021');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(3);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao180', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONDOMÍNIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.03');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao181', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao182', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao183', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao184', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao185', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao186', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LOCAÇÃO. ARRENDAMENTO. COMODATO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.15');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao187', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ALIENAÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao188', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VENDA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao189', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao190', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao191', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.24');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao192', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESAPROPRIAÇÃO. REINTEGRAÇÃO DE POSSE. REIVINDICAÇÃO DE DOMÍNIO. TOMBAMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao193', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OBRAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao194', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REFORMA. RECUPERAÇÃO. RESTAURAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao195', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTRUÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.42');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao196', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE MANUTENÇÃO (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao197', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MANUTENÇÃO DE ELEVADORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao198', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MANUTENÇÃO DE AR-CONDICIONADO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao199', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MANUTENÇÃO DE SUBESTAÇÕES E GERADORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.53');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao200', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LIMPEZA. IMUNIZAÇÃO. DESINFESTAÇÃO (INCLUSIVE PARA JARDINS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('041.54');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao201', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS SERVIÇOS DE MANUTENÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('041.59');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao202', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('042');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao203', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('042.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao204', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA (INCLUSIVE COMPRA POR IMPORTAÇÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao205', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA (INCLUSIVE COMPRA POR IMPORTAÇÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao206', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO. DOAÇÃO. PERMUTA. TRANSFERÊNCIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao207', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CADASTRO. LICENCIAMENTO. EMPLACAMENTO. TOMBAMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('Até a alienação');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao208', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ALIENAÇÃO (INCLUSIVE LICITAÇÕES)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('042.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao209', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VENDA (INCLUSIVE LEILÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao210', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO. DOAÇÃO. PERMUTA. TRANSFERÊNCIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao211', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ABASTECIMENTO. LIMPEZA. MANUTENÇÃO. REPARO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao212', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ACIDENTES. INFRAÇÕES. MULTAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao213', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A VEÍCULOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('042.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao214', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE USO DE VEÍCULOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.91');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao215', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REQUISIÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.911');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao216', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUTORIZAÇÃO PARA USO FORA DO HORÁRIO DE EXPEDIENTE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.912');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao217', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ESTACIONAMENTO. GARAGEM');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('042.913');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao218', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('043');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao219', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO (INCLUSIVE RMBI)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('044');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao220', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A PATRIMÔNIO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('049');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao221', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('GUARDA E SEGURANÇA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao222', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE VIGILÂNCIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao223', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SEGUROS (INCLUSIVE DE VEÍCULOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao224', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PREVENÇÃO DE INCÊNDIO: TREINAMENTO DE PESSOAL, INSTALAÇÃO E MANUTENÇÃO DE EXTINTORES, INSPEÇÕES PERIÓDICAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao225', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTITUIÇÃO DE BRIGADAS DE INCÊNDIO, PLANOS, PROJETOS E RELATÓRIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.131');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao226', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SINISTRO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('Até a conclusão do caso');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao227', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE PORTARIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.15');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao228', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO DE OCORRÊNCIAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.151');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao229', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MUDANÇAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('049.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao230', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PARA OUTROS IMÓVEIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao231', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DENTRO DO MESMO IMÓVEL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao232', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('USO DE DEPENDÊNCIAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('049.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao233', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ORÇAMENTO E FINANÇAS: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('050');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao234', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AUDITORIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('050.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao235', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ORÇAMENTO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('051');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao236', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('051.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao237', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PREVISÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao238', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROPOSTA ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao239', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('QUADRO DE DETALHAMENTO DE DESPESA (QDD)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao240', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CRÉDITOS ADICIONAIS: CRÉDITO SUPLEMENTAR. CRÉDITO ESPECIAL. CRÉDITO EXTRAORDINÁRIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao241', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('051.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao242', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESCENTRALIZAÇÃO DE RECURSOS (DISTRIBUIÇÃO ORÇAMENTÁRIA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao243', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ACOMPANHAMENTO DE DESPESA MENSAL (PESSOAL/DÍVIDA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao244', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANO OPERATIVO. CRONOGRAMA DE DESEMBOLSO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('051.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao245', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FINANÇAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('052');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao246', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO FINANCEIRA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('052.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao247', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECEITA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('052.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao248', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESPESA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('052.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao249', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FUNDOS ESPECIAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('053');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao250', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ESTÍMULOS FINANCEIROS E CREDITÍCIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('054');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao251', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OPERAÇÕES BANCÁRIAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('055');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao252', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PAGAMENTOS EM MOEDA ESTRANGEIRA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('055.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao253', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTA ÚNICA (INCLUSIVE ASSINATURAS AUTORIZADAS E EXTRATOS DE CONTAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('055.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao254', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS CONTAS: TIPO B, C e D (INCLUSIVE ASSINATURAS AUTORIZADAS E EXTRATOS DE CONTAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('055.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao255', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('BALANÇOS. BALANCETES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('056');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao256', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TOMADA DE CONTAS. PRESTAÇÃO DE CONTAS (INCLUSIVE PARECER DE APROVAÇÃO DAS CONTAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('057');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao257', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES A ORÇAMENTO E FINANÇAS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('059');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao258', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TRIBUTOS (IMPOSTOS E TAXAS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('059.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao259', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOCUMENTAÇÃO E INFORMAÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('060');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao260', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PUBLICAÇÃO DE MATÉRIAS NO DIÁRIO OFICIAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('060.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao261', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PUBLICAÇÃO DE MATÉRIAS NOS BOLETINS ADMINISTRATIVO, DE PESSOAL E DE SERVIÇO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('060.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao262', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PUBLICAÇÃO DE MATÉRIAS EM OUTROS PERIÓDICOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('060.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao263', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PRODUÇÃO EDITORIAL (INCLUSIVE EDIÇÃO OU COEDIÇÃO DE PUBLICAÇÕES EM GERAL PRODUZIDAS PELO ÓRGÃO EM QUALQUER SUPORTE)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('061');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao264', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EDITORAÇÃO. PROGRAMAÇÃO VISUAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('061.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao265', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DISTRIBUIÇÃO. PROMOÇÃO. DIVULGAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('061.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao266', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOCUMENTAÇÃO BIBLIOGRÁFICA (LIVROS, PERIÓDICOS, FOLHETOS E AUDIOVISUAIS)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('062');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao267', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMAS E MANUAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao268', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO (NO BRASIL E NO EXTERIOR)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('062.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao269', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA (INCLUSIVE ASSINATURAS DE PERIÓDICOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao270', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao271', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PERMUTA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao272', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao273', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CATALOGAÇÃO. CLASSIFICAÇÃO. INDEXAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao274', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REFERÊNCIA E CIRCULAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao275', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('062.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao276', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOCUMENTAÇÃO ARQUIVÍSTICA: GESTÃO DE DOCUMENTOS E SISTEMA DE ARQUIVOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('063');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao277', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMAS E MANUAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(7);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao278', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PRODUÇÃO DE DOCUMENTOS. LEVANTAMENTO. FLUXO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao279', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DIAGNÓSTICO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao280', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROTOCOLO: RECEPÇÃO, TRAMITAÇÃO E EXPEDIÇÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao281', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSISTÊNCIA TÉCNICA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao282', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CLASSIFICAÇÃO E ARQUIVAMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao283', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CÓDIGO DE CLASSIFICAÇÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('O prazo total de guarda do documento é de 100 anos, devendo o órgão permanecer com um exemplar por igual período. Ver observação');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao284', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('POLÍTICA DE ACESSO AOS DOCUMENTOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao285', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSULTAS. EMPRÉSTIMOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('1 ano após a devolução');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao286', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESTINAÇÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('063.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao287', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ANÁLISE. AVALIAÇÃO. SELEÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.61');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao288', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TABELA DE TEMPORALIDADE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.611');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('O prazo total de guarda do documento é de 100 anos, devendo o órgão permanecer com um exemplar por igual período. Ver observação');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao289', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ELIMINAÇÃO: TERMOS, LISTAGENS E EDITAIS DE CIÊNCIA DE ELIMINAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.62');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao290', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('TRANSFERÊNCIA. RECOLHIMENTO. GUIAS E TERMOS DE TRANSFERÊNCIA, GUIAS, RELAÇÕES E TERMOS DE RECOLHIMENTO, LISTAGENS DESCRITIVAS DO ACERVO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('063.63');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao291', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DOCUMENTAÇÃO MUSEOLÓGICA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('064');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao292', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REPRODUÇÃO DE DOCUMENTOS, ESTUDOS, PROJETOS E NORMAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('065');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao293', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSERVAÇÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('066');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao294', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DESINFESTAÇÃO. HIGIENIZAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('066.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao295', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ARMAZENAMENTO. DEPÓSITOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('066.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao296', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RESTAURAÇÃO DE DOCUMENTOS (INCLUSIVE ENCADERNAÇÃO)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('066.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao297', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INFORMÁTICA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('067');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao298', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANOS E PROJETOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('067.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao299', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAS. SISTEMAS. REDES (INCLUSIVE LICENÇA E REGISTRO DE USO E COMPRA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('067.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao300', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MANUAIS TÉCNICOS (EXEMPLARES ÚNICOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('067.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao301', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MANUAIS DO USUÁRIO (EXEMPLARES ÚNICOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('067.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao302', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSISTÊNCIA TÉCNICA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('067.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao303', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES À DOCUMENTAÇÃO E INFORMAÇÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('069');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao304', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICAÇÕES: NORMAS, REGULAMENTAÇÕES, DIRETRIZES, PROCEDIMENTOS, ESTUDOS E/OU DECISÕES DE CARÁTER GERAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('070');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao305', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO POSTAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('071');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao306', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE ENTREGA EXPRESSA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('071.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao307', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NACIONAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('071.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao308', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INTERNACIONAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('071.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao309', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE COLETA, TRANSPORTE E ENTREGA DE CORRESPONDÊNCIA AGRUPADA – MALOTE');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('071.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao310', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('MALA OFICIAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('071.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao311', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS SERVIÇOS POSTAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('071.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao312', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE RÁDIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('072');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao313', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO. MANUTENÇÃO. REPARO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('072.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao314', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TELEX');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('073');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao315', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO. MANUTENÇÃO. REPARO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('073.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao316', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO TELEFÔNICO (INCLUSIVE AUTORIZAÇÃO PARA LIGAÇÕES INTERURBANAS). FAC-SÍMILE (FAX)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('074');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao317', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO. TRANSFERÊNCIA. MANUTENÇÃO. REPARO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('074.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao318', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('LISTAS TELEFÔNICAS INTERNAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('074.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao319', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTAS TELEFÔNICAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('074.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao320', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE TRANSMISSÃO DE DADOS, VOZ E IMAGEM');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('075');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA DATA DE APROCAÇÃO DAS CONTAS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao321', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES À COMUNICAÇÕES');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('079');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao322', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('OUTROS ASSUNTOS REFERENTES À ADMINISTRAÇÃO GERAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('090');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao323', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AÇÕES JUDICIAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('091');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao324', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSUNTOS DIVERSOS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('900');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao325', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SOLENIDADES. COMEMORAÇÕES. HOMENAGENS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('910');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao326', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO, PROGRAMAÇÃO, DISCURSOS, PALESTRAS E TRABALHOS APRESENTADOS POR TÉCNICOS DO ÓRGÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('911');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao327', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONGRESSOS. CONFERÊNCIAS. SEMINÁRIOS. SIMPÓSIOS. ENCONTROS. CONVENÇÕES. CICLOS DE PALESTRAS. MESAS REDONDAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('920');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao328', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO, PROGRAMAÇÃO, DISCURSOS, PALESTRAS E TRABALHOS APRESENTADOS POR TÉCNICOS DO ÓRGÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('921');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao329', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('FEIRAS. SALÕES. EXPOSIÇÕES. MOSTRAS. FESTAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('930');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao330', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO, PROGRAMAÇÃO, DISCURSOS, PALESTRAS E TRABALHOS APRESENTADOS POR TÉCNICOS DO ÓRGÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('931');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao331', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONCURSOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('932');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao332', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO, NORMAS, EDITAIS, HABILITAÇÃO DOS CANDIDATOS, JULGAMENTO DA BANCA, TRABALHOS CONCORRENTES, PREMIAÇÃO E RECURSOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('933');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao333', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VISITAS E VISITANTES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('940');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao334', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('VISITAS E VISITANTES');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('990');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao335', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('APRESENTAÇÃO. RECOMENDAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('991');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao336', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICADOS E INFORMES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('992');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao337', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AGRADECIMENTOS. CONVITES. FELICITAÇÕES. PÊSAMES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('993');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao338', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROTESTOS. REIVINDICAÇÕES. SUGESTÕES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('994');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao339', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PEDIDOS, OFERECIMENTOS E INFORMAÇÕES DIVERSAS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('995');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao340', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ASSOCIAÇÕES: CULTURAIS, DE AMIGOS E DE SERVIDORES');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('996');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao341', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTENCIOSO JUDICIAL');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('100');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao342', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO E REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('101');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao343', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('102');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao344', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COORDENAÇÃO E SUPERVISÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('103');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao345', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REPRESENTAÇÃO E DEFESA JUDICIAL DOS PODERES DA UNIÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('110');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao346', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DA ADMINISTRAÇÃO DIRETA E INDIRETA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('111');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS DO TRÂNSITO EM JULGADO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao347', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PRECATÓRIOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('111.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS DA QUITAÇÃO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao348', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REQUISIÇÕES DE PEQUENO VALOR');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('111.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS DA QUITAÇÃO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao349', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COBRANÇA JUDICIAL DE CRÉDITOS E PATRIMÔNIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('111.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS DO TRANSITO EM JULGADO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao350', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DO AGENTE PÚBLICO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('112');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS DO TRANSITO EM JULGADO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao351', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ANÁLISE DA FORÇA EXECUTÓRIA');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('120');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('20 ANOS DO TRANSITO EM JULGADO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao352', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COOPERAÇÃO JURÍDICA INTERNACIONAL');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('130');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANSO DO TRANSITO EM JULGADO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao353', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTENCIOSO ADMINISTRATIVO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('200');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao354', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO E REGULAMENTAÇÃO (CA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('201');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao355', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO (CA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('202');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao356', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COORDENAÇÃO E SUPERVISÃO (CA)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('203');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao357', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REPRESENTAÇÃO E DEFESA EXTRAJUDICIAL DOS PODERES DA UNIÃO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('210');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao358', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('DA ADMINISTRAÇÃO DIRETA E INDIRETA DA UNIÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('211');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao359', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PROBIDADE ADMINISTRATIVA');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('211.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao360', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECUPEARÇÃO DE CRÉDITOS E PATRIMÔNIO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('211.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS A PARTIR DA QUITAÇÃO');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao361', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COMBATE À CORRUPÇÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('211.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao362', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COBRANÇA EXTRAJUDICIAL DE CRÉDITOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('211.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao363', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('AGENTE PÚBLICO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('212');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao364', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONSULTORIA E ASSESSORAMENTO JURÍDICO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('300');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao365', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO E REGULAMENTAÇÃO (CAJ)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('301');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao366', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO (CAJ)');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('302');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao367', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('COORDENAÇÃO E SUPERVISÃO (CAJ)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('303');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao368', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ANÁLISE DE LEGALIDADE DO ATO ADMINISTRATIVO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('304');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(40);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao369', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('SUBSÍDIO PARA DEFESA EM JUÍZO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('305');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 (CINCO) ANOS A PARTIR DO TRANSITO EM JULGADO DO PROCESSO A QUE SE REFERE');
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao370', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONCILIAÇÃO E ARBITRAGEM');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('306');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao371', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('UNIFORMIZAÇÃO DE ENTENDIMENTO JURÍDICO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('307');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao372', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('EDIÇÃO DE ATOS NORMATIVOS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('308');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao373', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('CONTENCIOSO INTERNACIONAL E ESTRANGEIRO');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('400');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao374', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO E REGULAMENTAÇÃO (CIE)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('401');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao375', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO (CIE)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('402');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao376', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ATUAÇÃO E FOROS ESTRANGEIROS');
        $classificacao->setPermissaoUso(false);
        $classificacao->setCodigo('410');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao(null);
        $this->addReference('Classificacao377', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('REPRESENTAÇÃO E DEFES JURÍDICA EM DEMANDA RELACIONADAS À UNIÃO');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('411');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao378', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('RECUPERAÇÃO INTERNACIONAL DE ATIVOS (PATRIMÔNIO E CRÉDITOS)');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('412');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao379', $classificacao);
        $manager->persist($classificacao);

        $classificacao = new Classificacao();
        $classificacao->setNome('ATUAÇÃO EM ORGANISMOS INTERNACIONAIS');
        $classificacao->setPermissaoUso(true);
        $classificacao->setCodigo('420');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(30);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $this->addReference('Classificacao380', $classificacao);
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao2', Classificacao::class, Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao3', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao4', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao5', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao6', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao7', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao8', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao9', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao10', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao11', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao10', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao12', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao13', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao12', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao14', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao12', Classificacao::class, Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao15', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao12', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao16', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao12', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao17', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao12', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao18', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao6', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao19', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao18', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao20', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao24', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao25', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao26', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao27', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao28', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao29', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao28', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao30', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao28', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao28', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao33', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao34', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao35', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao36', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao37', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao38', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao39', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao40', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao41', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao42', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao43', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao44', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao32', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao45', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao46', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao47', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao48', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao49', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao50', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao53', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao54', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao45', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao55', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao56', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao57', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao58', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao59', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao60', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao61', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao62', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao63', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao64', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao65', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao66', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao67', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao68', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao69', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao70', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao71', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao72', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao73', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao74', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao75', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao76', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao77', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao78', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao79', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao80', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao81', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao82', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao83', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao84', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao85', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao86', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao87', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao88', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao89', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao90', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao91', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao92', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao93', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao94', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao95', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao96', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao97', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao98', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao99', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao100', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao55', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao101', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao102', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao101', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao103', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao101', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao104', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao101', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao105', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao106', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao107', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao108', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao109', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao110', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao111', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao112', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao113', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao114', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao115', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao116', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao117', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao118', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao119', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao120', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao121', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao122', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao123', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao124', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao125', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao126', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao127', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao105', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao128', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao20', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao129', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao130', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao131', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao132', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao133', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao134', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao135', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao136', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao137', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao138', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao139', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao140', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao141', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao128', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao142', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao143', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao144', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao145', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao146', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao147', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao148', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao149', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao150', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao151', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao152', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao153', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao154', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao155', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao146', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao156', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao157', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao158', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao159', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao160', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao161', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao162', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao156', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao163', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao164', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao163', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao165', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao163', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao166', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao167', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao166', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao168', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao166', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao169', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao170', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao169', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao171', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao169', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao172', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao142', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao173', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao174', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao173', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao175', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao176', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao177', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao178', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao179', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao180', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao181', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao182', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao183', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao184', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao185', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao186', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao187', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao188', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao189', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao190', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao191', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao192', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao193', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao194', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao195', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao196', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao197', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao198', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao199', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao200', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao201', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao202', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao174', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao203', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao173', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao204', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao205', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao206', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao207', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao208', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao209', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao210', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao211', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao212', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao213', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao214', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao215', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao216', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao217', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao218', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao203', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao219', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao173', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao220', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao173', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao221', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao173', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao222', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao223', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao224', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao225', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao226', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao227', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao228', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao229', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao230', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao231', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao232', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao233', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao221', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao234', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao235', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao236', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao237', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao238', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao239', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao240', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao241', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao242', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao243', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao244', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao245', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao236', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao246', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao247', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao246', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao248', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao246', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao249', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao246', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao250', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao251', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao252', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao253', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao252', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao254', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao252', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao255', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao252', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao256', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao257', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao258', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao234', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao259', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao258', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao260', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao261', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao262', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao263', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao264', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao265', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao264', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao266', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao264', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao267', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao268', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao269', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao270', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao271', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao272', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao273', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao274', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao275', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao276', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao267', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao277', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao278', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao279', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao280', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao281', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao282', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao283', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao284', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao285', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao286', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao287', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao288', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao289', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao290', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao291', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao277', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao292', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao293', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao294', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao295', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao294', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao296', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao294', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao297', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao294', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao298', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao294', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao299', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao298', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao300', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao298', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao301', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao298', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao302', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao298', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao303', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao298', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao304', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao260', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao305', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao306', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao307', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao308', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao309', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao310', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao311', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao312', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao306', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao313', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao314', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao313', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao315', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao316', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao315', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao317', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao318', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao317', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao319', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao317', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao320', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao317', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao321', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao322', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao305', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao323', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao1', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao324', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao323', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao326', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao325', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao327', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao326', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao328', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao325', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao329', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao328', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao330', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao325', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao331', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao330', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao332', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao330', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao333', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao330', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao334', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao325', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao335', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao325', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao336', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao337', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao338', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao339', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao340', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao341', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao335', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao343', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao344', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao345', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao346', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao347', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao346', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao348', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao347', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao349', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao347', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao350', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao347', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao351', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao346', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao352', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao353', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao342', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao355', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao354', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao356', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao354', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao357', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao354', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao358', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao354', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao359', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao354', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao360', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao359', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao361', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao360', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao362', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao360', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao363', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao359', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao364', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao359', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao366', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao367', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao368', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao369', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao370', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao371', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao372', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao373', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao365', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao375', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao374', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao376', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao374', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao377', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao374', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao378', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao377', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao379', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao377', Classificacao::class));
        $manager->persist($classificacao);

        $classificacao = $this->getReference('Classificacao380', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao374', Classificacao::class));
        $manager->persist($classificacao);

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
        return ['prod', 'dev', 'test'];
    }
}
