<?php
#PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadClassificacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

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
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('000');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-000', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÃO INTERINSTITUCIONAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('001');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(20);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-001', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ATENDIMENTO AO CIDADÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('002');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-002', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('002.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-002.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ACESSO À INFORMAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('002.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-002.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PEDIDO DE ACESSO À INFORMAÇÃO E RECURSO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('002.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ O TÉRMINO DO ATENDIMENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O RESULTADO DO PROVIMENTO DO RECURSO EM ÚLTIMA INSTÂNCIA, NO CASO DE INDEFERIMENTO DO PEDIDO DE ACESSO.');
        $this->addReference('Classificacao-002.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ACOMPANHAMENTO DO ATENDIMENTO AO CIDADÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('002.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ESTEJAM RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-002.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE SATISFAÇÃO DO USUÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('002.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-002.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE E FISCALIZAÇÃO DA GESTÃO PÚBLICA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('003');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ESTEJAM RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-003', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('003.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-003.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE INTERNO. AUDITORIA INTERNA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('003.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-003.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AÇÃO PREVENTIVA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('003.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-003.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CORREIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('003.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-003.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ASSESSORAMENTO JURÍDICO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('004');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ORIENTAÇÃO TÉCNICA E NORMATIVA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('004.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('UNIFORMIZAÇÃO DO ENTENDIMENTO JURÍDICO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('004.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ANÁLISE DOS INSTRUMENTOS ADMINISTRATIVOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('004.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ O TÉRMINO DA ANÁLISE');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ATUAÇÃO EM CONTENCIOSO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('004.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REPRESENTAÇÃO EXTRAJUDICIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('004.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A SOLUÇÃO DO LITÍGIO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REPRESENTAÇÃO JUDICIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('004.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ O TRÂNSITO EM JULGADO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-004.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PARTICIPAÇÃO EM ÓRGÃOS COLEGIADOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('005');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-005', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO E ATUAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('005.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-005.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('005.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-005.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ORGANIZAÇÃO E FUNCIONAMENTO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('010');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-010', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('010.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-010.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ORGANIZAÇÃO ADMINISTRATIVA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('011');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS ESTUDOS PRELIMINARES OU AS VERSÕES NÃO IMPLEMENTADAS DAS MUDANÇAS ESTRUTURAIS.');
        $this->addReference('Classificacao-011', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('HABILITAÇÃO JURÍDICA E REGULARIZAÇÃO FISCAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('012');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-012', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COORDENAÇÃO E GESTÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('013');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-013', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('013.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-013.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO DE DELIBERAÇÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('013.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-013.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PREENCHIMENTO DE FUNÇÃO DE DIREÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('014');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-014', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NOMEAÇÃO E ATUAÇÃO DA COMISSÃO ELEITORAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('014.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-014.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('014.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-014.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VOTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('014.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS DA HOMOLOGAÇÃO DO EVENTO, AS CÉDULAS DE VOTAÇÃO.');
        $this->addReference('Classificacao-014.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DIVULGAÇÃO DOS RESULTADOS E INTERPOSIÇÃO DE RECURSOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('014.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS. ELIMINAR, APÓS 2 ANOS DA HOMOLOGAÇÃO DO EVENTO, OS DOCUMENTOS DE RECURSOS INDEFERIDOS.');
        $this->addReference('Classificacao-014.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO INSTITUCIONAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('015');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-015', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO INSTITUCIONAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('015.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-015.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ACOMPANHAMENTO DAS ATIVIDADES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('015.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-015.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO DA GESTÃO INSTITUCIONAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('015.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-015.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELABORAÇÃO DOS INSTRUMENTOS DE AVALIAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('015.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-015.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO E ACOMPANHAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('015.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-015.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CERTIFICAÇÃO DA CONFORMIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('015.33');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-015.33', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE PROCESSOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('016');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-016', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO DO MAPEAMENTO DE PROCESSOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('016.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-016.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO E ACOMPANHAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('016.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-016.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RESULTADO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('016.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-016.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MODELAGEM DE PROCESSOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('016.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-016.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GERENCIAMENTO DE DESEMPENHO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('016.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-016.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO AMBIENTAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('017');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-017', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROTEÇÃO AMBIENTAL INTERNA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('017.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ARQUIVAR UM EXEMPLAR DO MATERIAL DE DIVULGAÇÃO PRODUZIDO PARA CADA EVENTO.');
        $this->addReference('Classificacao-017.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROTEÇÃO AMBIENTAL EXTERNA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('017.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-017.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('018');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CONTRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-018', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À ORGANIZAÇÃO E FUNCIONAMENTO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('019');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-019', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICAÇÃO SOCIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-019.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICAÇÃO EXTERNA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('019.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-019.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CREDENCIAMENTO DE JORNALISTAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.111');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-019.111', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÃO COM A IMPRENSA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.112');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ARQUIVAR OS DOCUMENTOS CUJAS INFORMAÇÕES REFLITAM A POLÍTICA DO ÓRGÃO OU ENTIDADE.');
        $this->addReference('Classificacao-019.112', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELABORAÇÃO DE CAMPANHAS PUBLICITÁRIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.113');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-019.113', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMUNICAÇÃO INTERNA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ARQUIVAR OS DOCUMENTOS CUJAS INFORMAÇÕES REFLITAM A POLÍTICA DO ÓRGÃO OU ENTIDADE.');
        $this->addReference('Classificacao-019.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AÇÃO DE RESPONSABILIDADE SOCIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('019.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(9);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-019.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE PESSOAS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('020');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('IMPLEMENTAÇÃO DAS POLÍTICAS DE PESSOAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('020.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.02', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO DA FORÇA DE TRABALHO. PREVISÃO DE PESSOAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.021');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.021', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO, EXTINÇÃO, CLASSIFICAÇÃO, TRANSFORMAÇÃO E TRANSPOSIÇÃO DE CARGOS E DE CARREIRAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.022');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.022', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELACIONAMENTO COM ENTIDADES REPRESENTATIVAS DE CLASSES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('020.03');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.03', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÃO COM SINDICATOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.031');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.031', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTOS REIVINDICATÓRIOS. GREVES. PARALISAÇÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.032');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.032', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÃO COM CONSELHOS PROFISSIONAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.033');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAÇÃO');
        $this->addReference('Classificacao-020.033', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ASSENTAMENTO FUNCIONAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('020.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIDORES E EMPREGADOS PÚBLICOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O SERVIDOR MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* TRANSFERIR OS DOCUMENTOS PARA FASE INTERMEDIÁRIA APÓS O  TÉRMINO  DO  VÍNCULO,  SENDO  O  PRAZO  TOTAL  DE  GUARDA  DOS DOCUMENTOS DE 100 ANOS.');
        $this->addReference('Classificacao-020.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIDORES TEMPORÁRIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O SERVIDOR MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* TRANSFERIR OS DOCUMENTOS PARA FASE INTERMEDIÁRIA APÓS O  TÉRMINO  DO  VÍNCULO,  SENDO  O  PRAZO  TOTAL  DE  GUARDA  DOS DOCUMENTOS DE 100 ANOS.');
        $this->addReference('Classificacao-020.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RESIDENTES E ESTAGIÁRIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O SERVIDOR MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* TRANSFERIR OS DOCUMENTOS PARA FASE INTERMEDIÁRIA APÓS O TÉRMINO DO VÍNCULO, SENDO O PRAZO TOTAL DE GUARDA DOS DOCUMENTOS DE 100 ANOS.');
        $this->addReference('Classificacao-020.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OCUPANTES DE CARGO COMISSIONADO E DE FUNÇÃO DE CONFIANÇA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O SERVIDOR MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* TRANSFERIR OS DOCUMENTOS PARA FASE INTERMEDIÁRIA APÓS O TÉRMINO DO VÍNCULO, SENDO O PRAZO TOTAL DE GUARDA DOS DOCUMENTOS DE 100 ANOS.');
        $this->addReference('Classificacao-020.14', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('IDENTIFICAÇÃO FUNCIONAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('020.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O SERVIDOR MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-020.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECRUTAMENTO E SELEÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('021');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-021', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO DO PROCESSO SELETIVO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('021.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-021.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('021.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS.');
        $this->addReference('Classificacao-021.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE APLICAÇÃO DE PROVAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('021.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS.');
        $this->addReference('Classificacao-021.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CORREÇÃO DE PROVAS. AVALIAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('021.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS.');
        $this->addReference('Classificacao-021.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DIVULGAÇÃO DOS RESULTADOS E INTERPOSIÇÃO DE RECURSOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('021.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS. ELIMINAR, APÓS 2 ANOS DA HOMOLOGAÇÃO DO EVENTO, OS DOCUMENTOS DE RECURSOS INDEFERIDOS.');
        $this->addReference('Classificacao-021.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROVIMENTO, MOVIMENTAÇÃO E VACÂNCIA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('022');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROVIMENTO DE CARGO PÚBLICO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTAÇÃO DE PESSOAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('022.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LOTAÇÃO, EXERCÍCIO E PERMUTA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO. REQUISIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REMOÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REDISTRIBUIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SUBSTITUIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO DE DESEMPENHO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('022.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CUMPRIMENTO DE ESTÁGIO PROBATÓRIO. HOMOLOGAÇÃO DA ESTABILIDADE.');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.61');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.61', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CUMPRIMENTO DE PERÍODO DE EXPERIÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.62');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.62', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO E PROGRESSÃO FUNCIONAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.63');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.63', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VACÂNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('022.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-022.7', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÃO DE DIREITOS E VANTAGENS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PAGAMENTO DE VENCIMENTOS. REMUNERAÇÕES. SALÁRIOS. PROVENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FOLHAS DE PAGAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REESTRUTURAÇÃO E ALTERAÇÃO SALARIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ABONO PROVISÓRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ABONO DE PERMANÊNCIA EM SERVIÇO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DA APOSENTADORIA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.14', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GRATIFICAÇÕES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.15');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.15', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FUNÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.151');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.151', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('JETONS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.152');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.152', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CARGOS EM COMISSÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.153');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.153', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NATALINA. DÉCIMO TERCEIRO SALÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.154');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.154', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESEMPENHO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.155');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.155', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ENCARGO DE CURSO E CONCURSO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.156');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.156', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TITULAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.157');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.157', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADICIONAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.16');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.16', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TEMPO DE SERVIÇO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.161');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.161', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NOTURNO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.162');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.162', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PERICULOSIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.163');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.163', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSALUBRIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.164');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.164', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ATIVIDADE PENOSA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.165');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.165', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO EXTRAORDINÁRIO. HORAS EXTRAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.166');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.166', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('UM TERÇO DE FÉRIAS. ABONO PECUNIÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.167');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.167', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESCONTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.17');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.17', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO SINDICAL, ASSISTENCIAL E CONFEDERATIVA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.171');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.171', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO PARA O PLANO DE SEGURIDADE SOCIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.172');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.172', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('IMPOSTO DE RENDA RETIDO NA FONTE (IRRF)');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.173');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.173', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÃO ALIMENTÍCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.174');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.174', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSIGNAÇÕES FACULTATIVAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.175');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(7);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA A CONSIGNAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.175', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OBRIGAÇÕES TRABALHISTAS E ESTATUTÁRIAS, ENCARGOS PATRONAIS E RECOLHIMENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.18');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.18', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMA DE FORMAÇÃO DO PATRIMÔNIO DO SERVIDOR PÚBLICO (PASEP). PROGRAMA DE INTEGRAÇÃO SOCIAL (PIS)');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.181');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.181', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FUNDO DE GARANTIA DO TEMPO DE SERVIÇO (FGTS)');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.182');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.182', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO SINDICAL DO EMPREGADOR');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.183');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.183', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRIBUIÇÃO PARA O PLANO DE SEGURIDADE SOCIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.184');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.184', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('IMPOSTO DE RENDA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.185');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.185', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LEI DOS DOIS TERÇOS. RELAÇÃO ANUAL DE INFORMAÇÕES SOCIAIS (RAIS)');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.186');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.186', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES AO PAGAMENTO DE VENCIMENTOS. REMUNERAÇÕES. SALÁRIOS. PROVENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.19');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.19', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RETIFICAÇÃO DE PAGAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.191');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.191', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FÉRIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LICENÇAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AFASTAMENTOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AUXÍLIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REEMBOLSO DE DESPESAS. INDENIZAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.7', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MUDANÇA DE DOMICÍLIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.71');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.71', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LOCOMOÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.72');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.72', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RESSARCIMENTO DE PLANO DE SAÚDE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.73');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.73', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À CONCESSÃO DE DIREITOS E VANTAGENS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('023.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.9', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE SEGURO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.91');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.91', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OCUPAÇÃO DE IMÓVEL FUNCIONAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.92');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO PERMANECER A OCUPAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-023.92', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FORNECIMENTO DE TRANSPORTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('023.93');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-023.93', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CAPACITAÇÃO DO SERVIDOR');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('024');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DE GUARDA E A DESTINAÇÃO FINAL DOS ASSENTAMENTOS FUNCIONAIS PARA OS DOCUMENTOS COMPROBATÓRIOS DE PARTICIPAÇÃO.');
        $this->addReference('Classificacao-024', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO DA CAPACITAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO DE CURSOS PELO ÓRGÃO E ENTIDADE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('024.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO E FREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO E RESULTADOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PARTICIPAÇÃO EM CURSOS PROMOVIDOS POR OUTROS ÓRGÃOS E ENTIDADES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO DE ESTÁGIOS PELO ÓRGÃO E ENTIDADE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('024.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO E FREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO E RESULTADOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.33');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.33', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PARTICIPAÇÃO EM ESTÁGIOS PROMOVIDOS POR OUTROS ÓRGÃOS E ENTIDADES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÃO DE ESTÁGIOS E BOLSAS PARA ESTUDANTES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('024.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELAÇÃO COM INSTITUIÇÕES DE ENSINO E AGENTES DE INTEGRAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA O CONVÊNIO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANO DE ESTÁGIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('024.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO O ESTUDANTE MANTIVER O VÍNCULO COM A ADMINISTRAÇÃO PÚBLICA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-024.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO DA SAÚDE E BEM-ESTAR');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('025');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ASSISTÊNCIA À SAÚDE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('025.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CELEBRAÇÃO DE CONVÊNIOS DE PLANOS DE SAÚDE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-025.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ORIENTAÇÃO MÉDICA, NUTRICIONAL, ODONTOLÓGICA E PSICOLÓGICA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO DE ATIVIDADE FÍSICA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PRONTUÁRIO MÉDICO E ODONTOLÓGICO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.14');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.14', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PRESERVAÇÃO DA SAÚDE E HIGIENE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('025.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE RISCOS AMBIENTAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(15);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OFERTA DE SERVIÇOS DE REFEITÓRIOS, CANTINAS E COPAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SEGURANÇA DO TRABALHO. PREVENÇÃO DE ACIDENTES DE TRABALHO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('025.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTITUIÇÃO DA COMISSÃO INTERNA DE PREVENÇÃO DE ACIDENTES (CIPA)');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('025.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPOSIÇÃO E ATUAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.311');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.311', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.312');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.312', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO DE OCORRÊNCIAS DE ACIDENTES DE TRABALHO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('025.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-025.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÃO DE BENEFÍCIOS DE SEGURIDADE E PREVIDÊNCIA SOCIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('026');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADESÃO À PREVIDÊNCIA COMPLEMENTAR');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTAGEM E AVERBAÇÃO DE TEMPO DE SERVIÇO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DA APOSENTADORIA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.02', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SALÁRIO FAMÍLIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(19);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU 95 ANOS, NOS CASOS PREVISTOS NA LEGISLAÇÃO ESPECÍFICA PARA AS CONCESSÕES ESPECIAIS');
        $this->addReference('Classificacao-026.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SALÁRIO MATERNIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AUXÍLIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-026.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LICENÇAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('APOSENTADORIA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('026.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVALIDEZ PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPULSÓRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VOLUNTÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.53');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.53', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ESPECIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.54');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.54', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÃO POR MORTE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('026.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÃO PROVISÓRIA. PENSÃO TEMPORÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.61');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.61', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PENSÃO VITALÍCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.62');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.62', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À CONCESSÃO DE BENEFÍCIOS DE SEGURIDADE E PREVIDÊNCIA SOCIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('026.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.9', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AUXÍLIO RECLUSÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('026.91');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-026.91', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('APURAÇÃO DE RESPONSABILIDADE DISCIPLINAR');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('027');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-027', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVERIGUAÇÃO DE DENÚNCIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('027.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-027.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('APLICAÇÃO DE PENALIDADES DISCIPLINARES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('027.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(95);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-027.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AJUSTAMENTO DE CONDUTA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('027.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-027.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CUMPRIMENTO DE MISSÕES E VIAGENS A SERVIÇO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('028');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NO PAÍS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('028.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COM ÔNUS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('028.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-028.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COM ÔNUS LIMITADO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('028.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NO EXTERIOR');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('028.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COM ÔNUS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('028.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-028.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COM ÔNUS LIMITADO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('028.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SEM ÔNUS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('028.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(7);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-028.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DE PESSOAS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('029');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE ASSIDUIDADE E PONTUALIDADE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('029.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE FREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(52);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DEFINIÇÃO DO HORÁRIO DE EXPEDIENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSTITUIÇÃO DO PROGRAMA DE CRECHE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('029.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PUBLICAÇÃO E DIVULGAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO, SELEÇÃO, ADMISSÃO E RENOVAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA O VÍNCULO DO BENEFICIÁRIO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ACOMPANHAMENTO PEDAGÓGICO, MÉDICO E DO DESENVOLVIMENTO DA CRIANÇA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(10);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA O VÍNCULO DO BENEFICIÁRIO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO DO PROGRAMA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.24');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-029.24', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INCENTIVOS FUNCIONAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DELEGAÇÃO DE COMPETÊNCIA E PROCURAÇÃO');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao('UTILIZAR  OS  PRAZOS  DOS  DOCUMENTOS  FINANCEIROS  PARA  OS DOCUMENTOS REFERENTES AOS ORDENADORES DE DESPESAS.');
        $this->addReference('Classificacao-029.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE SERVIÇOS PROFISSIONAIS TRANSITÓRIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CON- TRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-029.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PETIÇÃO DE DIREITOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('029.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A SOLUÇÃO DA INTERPOSIÇÃO DE PEDIDO DE RECONSIDERAÇÃO OU DE RECURSO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-029.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE MATERIAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('030');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-030', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('030.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-030.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CADASTRAMENTO DE FORNECEDORES E DE PRESTADORES DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('030.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-030.02', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ESPECIFICAÇÃO, PADRONIZAÇÃO, CODIFICAÇÃO, PREVISÃO, IDENTIFICAÇÃO E CLASSIFICAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('030.03');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-030.03', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO E INCORPORAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('031');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS AÇÕES DE AQUISIÇÃO E INCORPORAÇÃO NÃO EFETIVADAS.');
        $this->addReference('Classificacao-031', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('031.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-031.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-031.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-031.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO E PERMUTA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('031.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-031.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DAÇÃO. ADJUDICAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('031.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-031.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO, COMODATO E EMPRÉSTIMO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('031.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-031.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.41', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.42');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-031.42', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LOCAÇÃO. ARRENDAMENTO MERCANTIL (LEASING)');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('031.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-031.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTAÇÃO DE MATERIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('032');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-032', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TERMOS DE RESPONSABILIDADE. CAUTELA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('032.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-032.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE ESTOQUE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('032.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('OBSERVAR OS PRAZOS DA LEGISLAÇÃO EM VIGOR PARA OS DOCUMENTOS REFERENTES A PRODUTOS E INSUMOS QUÍMICOS E OUTRAS SUBSTÂNCIAS ENTORPECENTES.');
        $this->addReference('Classificacao-032.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AUTORIZAÇÃO DE ENTRADA E SAÍDA DE MATERIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('032.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('OBSERVAR OS PRAZOS DA LEGISLAÇÃO EM VIGOR PARA OS DOCUMENTOS REFERENTES A PRODUTOS E INSUMOS QUÍMICOS E OUTRAS SUBSTÂNCIAS ENTORPECENTES.');
        $this->addReference('Classificacao-032.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECOLHIMENTO DE MATERIAL INSERVÍVEL AO DEPÓSITO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('032.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('OBSERVAR OS PRAZOS DA LEGISLAÇÃO EM VIGOR PARA OS DOCUMENTOS REFERENTES A PRODUTOS E INSUMOS QUÍMICOS E OUTRAS SUBSTÂNCIAS ENTORPECENTES.');
        $this->addReference('Classificacao-032.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TOMBAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('032.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A ALIENAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-032.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ALIENAÇÃO E BAIXA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS AÇÕES DE ALIENAÇÃO E BAIXA NÃO EFETIVADAS.');
        $this->addReference('Classificacao-033', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VENDA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-033.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-033.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-033.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO E PERMUTA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-033.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DAÇÃO. ADJUDICAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-033.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESFAZIMENTO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-033.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.41', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.42');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.42', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO, COMODATO E EMPRÉSTIMO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('033.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-033.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-033.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXTRAVIO, ROUBO, DESAPARECIMENTO, FURTO E AVARIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('033.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A CONCLUSÃO DO CASO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('OBSERVAR OS PRAZOS DA LEGISLAÇÃO EM VIGOR PARA OS DOCUMENTOS REFERENTES A PRODUTOS E INSUMOS QUÍMICOS E OUTRAS SUBSTÂNCIAS ENTORPECENTES.');
        $this->addReference('Classificacao-033.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('034');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CON- TRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-034', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO DE SERVIÇOS DE INSTALAÇÃO E MANUTENÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('035');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-035', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE MATERIAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('036');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-036', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMISSÃO DE INVENTÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('036.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-036.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO DE MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('036.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-036.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO DE MATERIAL DE CONSUMO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('036.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-036.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DE MATERIAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('039');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-039', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RACIONALIZAÇÃO DO USO DE MATERIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('039.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-039.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO E ATUAÇÃO DE GRUPOS DE TRABALHO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('039.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-039.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('039.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-039.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EMPRÉSTIMO E DEVOLUÇÃO DE MATERIAL PERMANENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('039.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-039.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE BENS PATRIMONIAIS E DE SERVIÇOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('040');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-040', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('040.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-040.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO E INCORPORAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS AÇÕES DE AQUISIÇÃO E INCORPORAÇÃO NÃO EFETIVADAS.');
        $this->addReference('Classificacao-041', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-041.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-041.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-041.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO E PERMUTA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DAÇÃO. ADJUDICAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROCRIAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO E COMODATO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.53');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-041.53', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LOCAÇÃO. ARRENDAMENTO MERCANTIL (LEASING). SUBLOCAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('041.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-041.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.61');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-041.61', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('041.62');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-041.62', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ALIENAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS AÇÕES DE ALIENAÇÃO NÃO EFETIVADAS.');
        $this->addReference('Classificacao-042', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VENDA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-042.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-042.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-042.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO E PERMUTA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-042.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS OS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-042.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DAÇÃO. ADJUDICAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A QUITAÇÃO TOTAL DA DÍVIDA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-042.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESAPROPRIAÇÃO E DESMEMBRAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-042.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CESSÃO E COMODATO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-042.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.53');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-042.53', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LOCAÇÃO. ARRENDAMENTO. SUBLOCAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-042.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BAIXA. DESFAZIMENTO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('042.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.7', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.71');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.71', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('042.72');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-042.72', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO CONDOMINIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('043');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO DOS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(3);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇOS DE CONDOMÍNIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-043.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REINTEGRAÇÃO DE POSSE. REIVINDICAÇÃO DE DOMÍNIO. DESPEJO DE PERMISSIONÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-043.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TOMBAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A ALIENAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSPEÇÃO PATRIMONIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(3);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MUDANÇA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('043.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PARA OUTROS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.61');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-043.61', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DENTRO DO MESMO IMÓVEL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.62');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-043.62', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('USO DE DEPENDÊNCIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('043.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-043.7', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO DA FROTA DE VEÍCULOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('044');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-044', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CADASTRAMENTO, LICENCIAMENTO E EMPLACAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A ALIENAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-044.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TOMBAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A ALIENAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-044.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OCORRÊNCIA DE SINISTROS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO OBSERVAR PARA OS ACIDENTES COM VÍTIMAS, O PRAZO TOTAL DE GUARDA DE 20 ANOS, APÓS A CONCLUSÃO DO CASO.');
        $this->addReference('Classificacao-044.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE USO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-044.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ESTACIONAMENTO. GARAGEM');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-044.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NOTIFICAÇÕES DE INFRAÇÕES E MULTAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('044.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-044.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('045');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CONTRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-045', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SEGURO PATRIMONIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FORNECIMENTO DE SERVIÇOS PÚBLICOS ESSENCIAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('045.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-045.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ÁGUA E ESGOTAMENTO SANITÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GÁS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ENERGIA ELÉTRICA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MANUTENÇÃO E REPARO DAS INSTALAÇÕES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('045.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-045.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELEVADORES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SISTEMAS CENTRAIS DE AR CONDICIONADO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SUBESTAÇÕES ELÉTRICAS E GERADORES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSERVAÇÃO PREDIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.24');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.24', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO DE OBRAS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('045.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-045.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTRUÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REFORMA. RECUPERAÇÃO. RESTAURAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADAPTAÇÃO DE USO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.33');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.33', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VIGILÂNCIA PATRIMONIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ABASTECIMENTO E MANUTENÇÃO DE VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ASSISTÊNCIA VETERINÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.6');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.6', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADESTRAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('045.7');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-045.7', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROTEÇÃO, GUARDA E SEGURANÇA PATRIMONIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('046');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PREVENÇÃO DE INCÊNDIO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('046.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO, ELABORAÇÃO E ACOMPANHAMENTO DE PROJETOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTITUIÇÃO DE BRIGADA VOLUNTÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO E MANUTENÇÃO DE EQUIPAMENTOS DE COMBATE A INCÊNDIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.13');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MONITORAMENTO. VIGILÂNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OCORRÊNCIA DE SINISTROS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A CONCLUSÃO DO CASO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-046.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE PORTARIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('046.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OBSERVAR PARA OS REGISTROS DE OCORRÊNCIAS, O PRAZO TOTAL DE GUARDA DE 10 ANOS.');
        $this->addReference('Classificacao-046.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE BENS PATRIMONIAIS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('047');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-047', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMISSÃO DE INVENTÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('047.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-047.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO DE BENS IMÓVEIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('047.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-047.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO DE VEÍCULOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('047.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-047.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO DE BENS SEMOVENTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('047.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-047.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DE BENS PATRIMONIAIS E DE SERVIÇOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('049');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-049', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RACIONALIZAÇÃO DO USO DE BENS E SERVIÇOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('049.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-049.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CRIAÇÃO E ATUAÇÃO DE GRUPOS DE TRABALHO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('049.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-049.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('049.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-049.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO ORÇAMENTÁRIA E FINANCEIRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('050');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-050', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('050.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-050.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONFORMIDADE DE REGISTRO DE GESTÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('050.02');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-050.02', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONFORMIDADE CONTÁBIL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('050.03');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-050.03', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('051');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-051', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('051.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES SOBRE  PREVISÃO  ORÇAMENTÁRIA  ENCONTREM-SE  RECAPITULA- DAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-051.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DETALHAMENTO DE DESPESA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('051.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-051.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO ORÇAMENTÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('051.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-051.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RETIFICAÇÃO ORÇAMENTÁRIA. CRÉDITOS ADICIONAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('051.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-051.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO FINANCEIRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('052');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-052', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO FINANCEIRA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO FINANCEIRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('052.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-052.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECEITA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('052.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-052.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECEITA CORRENFALSETE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.211');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.211', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECEITA DE CAPITAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.212');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.212', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INGRESSO EXTRAORÇAMENTÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.213');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.213', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESPESA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('052.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-052.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESPESA CORRENTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.221');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.221', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESPESA DE CAPITAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.222');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.222', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DEMONSTRAÇÃO CONTÁBIL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.23');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.23', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE FUNDOS ESPECIAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.24');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.24', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCESSÃO DE BENEFÍCIOS, ESTÍMULOS E INCENTIVOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('052.25');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-052.25', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FINANCEIROS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.251');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.251', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CREDITÍCIOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('052.252');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-052.252', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERAÇÃO BANCÁRIA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('053');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-053', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONCILIAÇÃO BANCÁRIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('053.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-053.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PAGAMENTO EM MOEDA ESTRANGEIRA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('053.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-053.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DA CONTA ÚNICA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('053.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-053.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE CONTAS CORRENTES BANCÁRIAS: TIPO A, B, C, D e E');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('053.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-053.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE CONTAS ESPECIAIS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('053.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-053.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE EXTERNO. AUDITORIA EXTERNA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('054');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-054', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PRESTAÇÃO DE CONTAS. TOMADA DE CONTAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('054.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-054.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TOMADA DE CONTAS ESPECIAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('054.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-054.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO ORÇAMENTÁRIA E FINANCEIRA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('059');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-059', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA E SUBSCRIÇÃO DE AÇÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('059.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-059.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECOLHIMENTO DE TRIBUTOS, IMPOSTOS E TAXAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('059.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-059.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FIXAÇÃO DE CUSTOS DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('059.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-059.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DEVOLUÇÃO AO ERÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('059.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-059.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RESTITUIÇÃO DE RENDAS ARRECADADAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('059.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-059.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DA DOCUMENTAÇÃO E DA INFORMAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('060');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-060', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('060.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-060.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE DOCUMENTOS DE ARQUIVO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('061');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSTITUIÇÃO DA COMISSÃO PERMANENTE DE AVALIAÇÃO DE DOCUMENTOS (CPAD)');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('061.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPOSIÇÃO E ATUAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.011');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.011', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OPERACIONALIZAÇÃO DE REUNIÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.012');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.012', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADOÇÃO E CONTROLE DOS PROCEDIMENTOS DE PROTOCOLO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ANÁLISE DA SITUAÇÃO ARQUIVÍSTICA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('LEVANTAMENTO DA PRODUÇÃO E DO FLUXO DOCUMENTAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A FINALIZAÇÃO DA ELABORAÇÃO DOS INSTRUMENTOS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELABORAÇÃO DOS INSTRUMENTOS TÉCNICOS DE GESTÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A FINALIZAÇÃO DA ELABORAÇÃO DOS INSTRUMENTOS');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, 2 ANOS APÓS A FINALIZAÇÃO DA ELABORAÇÃO, AS VERSÕES PRELIMINARES DOS INSTRUMENTOS, ASSIM COMO OS DEMAIS EXEMPLARES QUANDO OCORREREM ATUALIZAÇÕES.');
        $this->addReference('Classificacao-061.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('APLICAÇÃO DOS INSTRUMENTOS TÉCNICOS DE GESTÃO DE DOCUMENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('061.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CLASSIFICAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.51');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.51', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('061.52');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.52', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELIMINAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.521');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.521', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TRANSFERÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.522');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.522', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RECOLHIMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('061.523');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-061.523', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE ACERVOS BIBLIOGRÁFICO E MUSEOLÓGICO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('062');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-062', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AQUISIÇÃO E INCORPORAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('062.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS AÇÕES DE AQUISIÇÃO E INCORPORAÇÃO NÃO EFETIVADAS.');
        $this->addReference('Classificacao-062.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('COMPRA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-062.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-062.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PERMUTA');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-062.13', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROCESSAMENTO TÉCNICO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('062.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-062.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.21');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-062.21', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CATALOGAÇÃO, CLASSIFICAÇÃO E INDEXAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.22');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-062.22', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INVENTÁRIO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-062.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESINCORPORAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('062.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-062.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DOAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-062.41', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESCARTE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('062.42');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(4);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-062.42', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE ACESSO E DE MOVIMENTAÇÃO DE ACERVOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('063');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-063', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSULTAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('063.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-063.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EMPRÉSTIMOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('063.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(1);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A DEVOLUÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-063.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MOVIMENTAÇÃO DE ACERVOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('063.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-063.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONSERVAÇÃO E PRESERVAÇÃO DE ACERVOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('064');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-064', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO E MONITORAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-064.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESINFESTAÇÃO E HIGIENIZAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-064.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DAS ÁREAS DE ARMAZENAMENTO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-064.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REFORMATAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('064.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-064.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('MICROFILMAGEM');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-064.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DIGITALIZAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-064.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RESTAURAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('064.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-064.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PRODUÇÃO EDITORIAL');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('065');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-065', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EDIÇÃO. COEDIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('065.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-065.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EDITORAÇÃO E PROGRAMAÇÃO VISUAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('065.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-065.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO, DIVULGAÇÃO E DISTRIBUIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('065.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSA- ÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-065.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE TECNOLOGIA DA INFORMAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('066');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-066', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DESENVOLVIMENTO E CONTROLE DE SISTEMAS INFORMATIZADOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO DE EQUIPAMENTOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO DA INFRAESTRUTURA TECNOLÓGICA');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('066.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROJETO DE MANUTENÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GERENCIAMENTO E USO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO DE BANCO DE DADOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('066.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSTALAÇÃO E CONFIGURAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.41');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.41', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GERENCIAMENTO E USO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.42');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.42', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DE TECNOLOGIA DA INFORMAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('066.9');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.9', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DO SUPORTE TÉCNICO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('066.91');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-066.91', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('067');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CON- TRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-067', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DA DOCUMENTAÇÃO E DA INFORMAÇÃO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('069');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-069', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TRATAMENTO TÉCNICO DA DOCUMENTAÇÃO ARQUIVÍSTICA PERMANENTE');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('069.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-069.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ARRANJO E DESCRIÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('069.11');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A CONCLUSÃO DA ORGANIZAÇÃO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, 2 ANOS APÓS A CONCLUSÃO DA ORGANIZAÇÃO, AS VER- SÕES PRELIMINARES DAS PLANILHAS E DOS ESTUDOS DE APOIO.');
        $this->addReference('Classificacao-069.11', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ELABORAÇÃO DE INSTRUMENTOS DE PESQUISA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('069.12');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A FINALIZAÇÃO DA ELABORAÇÃO DOS INSTRU- MENTOS DE PESQUISA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, 2 ANOS APÓS A FINALIZAÇÃO DA ELABORAÇÃO, AS VERSÕES PRELIMINARES DOS INSTRUMENTOS ASSIM COMO OS DEMAIS EXEMPLARES QUANDO OCORREREM ATUALIZAÇÕES.');
        $this->addReference('Classificacao-069.12', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('FORNECIMENTO DE CÓPIAS DE DOCUMENTOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('069.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DOS DOCUMENTOS FINANCEIROS PARA AS TRANSAÇÕES QUE ENVOLVAM PAGAMENTO DE DESPESAS.');
        $this->addReference('Classificacao-069.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PUBLICAÇÃO DE MATÉRIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('069.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-069.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DOS SERVIÇOS POSTAIS E DE TELECOMUNICAÇÕES');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('070');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-070', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('070.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-070.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('071');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CONTRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-071', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO POSTAL');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('071.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-071.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE RADIOFREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('071.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-071.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TELEX');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('071.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-071.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TELEFONIA. SERVIÇO DE FAX');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('071.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-071.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TRANSMISSÃO DE DADOS, VOZ E IMAGEM');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('071.5');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO');
        $this->addReference('Classificacao-071.5', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EXECUÇÃO DE SERVIÇOS PELO ÓRGÃO E ENTIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('072');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-072', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AUTORIZAÇÃO E CONTROLE DE USO');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('073');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE RADIOFREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TELEX');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.2');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.2', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TELEFONIA. SERVIÇO DE FAX');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('073.3');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.3', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('TRANSFERÊNCIA DE PROPRIEDADE OU TITULARIDADE');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.31');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A CONCLUSÃO DA TRANSFERÊNCIA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.31', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('REGISTRO DE LIGAÇÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.32');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.32', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DIVULGAÇÃO DE LISTAS TELEFÔNICAS INTERNAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.33');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.33', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('SERVIÇO DE TRANSMISSÃO DE DADOS, VOZ E IMAGEM');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('073.4');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-073.4', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PESSOAL MILITAR - ver anexo 1');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('080');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-080', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('VAGA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('090');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-090', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('ADMINISTRAÇÃO DE ATIVIDADES ACESSÓRIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('900');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-900', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE EVENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('910');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES AOS EVENTOS NÃO EFETIVADOS.');
        $this->addReference('Classificacao-910', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('910.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-910.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PLANEJAMENTO E PROGRAMAÇÃO');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-911', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('DIVULGAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('912');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(1);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ARQUIVAR UM EXEMPLAR DO MATERIAL DE DIVULGAÇÃO DE CADA EVENTO.');
        $this->addReference('Classificacao-912', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('INSCRIÇÃO E CONTROLE DE FREQUÊNCIA');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('913');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-913', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('EMISSÃO DE CERTIFICADOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('914');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(3);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-914', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('AVALIAÇÃO DOS RESULTADOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('915');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS CUJAS INFORMAÇÕES ENCONTRAM-SE RECAPITULADAS OU CONSOLIDADAS EM OUTROS.');
        $this->addReference('Classificacao-915', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('HABILITAÇÃO, JULGAMENTO E RECURSOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('916');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* AGUARDAR O TÉRMINO DA AÇÃO, NO CASO DE INTERPOSIÇÃO DE RECURSOS. ELIMINAR, APÓS 2 ANOS DA HOMOLOGAÇÃO DO EVENTO, OS DOCUMENTOS DE RECURSOS INDEFERIDOS.');
        $this->addReference('Classificacao-916', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PREMIAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('917');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(2);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A HOMOLOGAÇÃO DO EVENTO');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-917', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTRATAÇÃO DE PRESTAÇÃO DE SERVIÇOS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('918');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento('5 ANOS A CONTAR DA APRO- VAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS**');
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ATÉ A APROVAÇÃO DAS CONTAS PELO TRIBUNAL DE CONTAS*');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('* OU ATÉ A APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ** OU 10 ANOS A CONTAR DA APRESENTAÇÃO DO RELATÓRIO DE GESTÃO ELIMINAR, APÓS 2 ANOS, OS DOCUMENTOS REFERENTES ÀS CONTRATAÇÕES NÃO EFETIVADAS.');
        $this->addReference('Classificacao-918', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À GESTÃO DE EVENTOS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('919');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-919', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PARTICIPAÇÃO EM EVENTOS PROMOVIDOS E REALIZADOS POR OUTRAS INSTITUIÇÕES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('919.1');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(5);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao('UTILIZAR OS PRAZOS DE GUARDA E A DESTINAÇÃO FINAL DOS ASSENTAMENTOS FUNCIONAIS PARA OS DOCUMENTOS COMPROBATÓRIOS DE PARTICIPAÇÃO.');
        $this->addReference('Classificacao-919.1', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROMOÇÃO DE VISITAS');
        $classificacao->setPermissaoUso(False);
        $classificacao->setCodigo('920');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-920', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('NORMATIZAÇÃO. REGULAMENTAÇÃO');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('920.01');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(5);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento('ENQUANTO VIGORA');
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-RECOLHIMENTO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-920.01', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('PROGRAMAÇÃO DE VISITAS');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-921', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('CONTROLE DE VISITAS E VISITANTES');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('922');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(2);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setModalidadeDestinacao($this->getReference('ModalidadeDestinacao-ELIMINAÇÃO', ModalidadeDestinacao::class));
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-922', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('OUTRAS AÇÕES REFERENTES À ADMINISTRAÇÃO DE ATIVIDADES ACESSÓRIAS');
        $classificacao->setPermissaoUso(True);
        $classificacao->setCodigo('990');
        $classificacao->setPrazoGuardaFaseIntermediariaDia(null);
        $classificacao->setPrazoGuardaFaseIntermediariaMes(null);
        $classificacao->setPrazoGuardaFaseIntermediariaAno(null);
        $classificacao->setPrazoGuardaFaseIntermediariaEvento(null);
        $classificacao->setPrazoGuardaFaseCorrenteDia(null);
        $classificacao->setPrazoGuardaFaseCorrenteMes(null);
        $classificacao->setPrazoGuardaFaseCorrenteAno(null);
        $classificacao->setPrazoGuardaFaseCorrenteEvento(null);
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-990', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('GESTÃO DE COMUNICAÇÕES EVENTUAIS');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-991', $classificacao);
        
        $manager->persist($classificacao);
        
        $classificacao = new Classificacao();
        $classificacao->setNome('RELACIONAMENTO COM ASSOCIAÇÕES CULTURAIS, DE AMIGOS E DE SERVIDORES');
        $classificacao->setPermissaoUso(True);
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
        $classificacao->setObservacao(null);
        $this->addReference('Classificacao-992', $classificacao);
        
        $manager->persist($classificacao);
        
        
        $classificacao = $this->getReference('Classificacao-001', Classificacao::class, Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-002', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-002', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-002.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-002.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-002.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-002', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-003', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-003.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-003', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-003.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-003', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-003.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-003', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-003.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-003', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-004.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-004.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-005', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-005.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-005', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-005.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-005', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-010', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-010.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-010', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-011', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-012', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-013', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-013.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-013', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-013.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-013', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-014', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-014.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-014', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-014.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-014', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-014.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-014', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-014.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-014', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-015.33', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-015.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-016', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-016', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-016', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-016', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-016.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-016', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-017', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-017.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-017', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-017.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-017', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-018', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.111', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019.11', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.112', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019.11', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.113', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019.11', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-019.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-019', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.02', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.021', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.02', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.022', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.02', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.03', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.031', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.03', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.032', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.03', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.033', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.03', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.14', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-020.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-020', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-021', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-021', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-021', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-021', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-021.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-021', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.61', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.62', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.63', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-022.7', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-022', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.14', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.15', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.151', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.152', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.153', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.154', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.155', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.156', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.157', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.15', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.16', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.161', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.162', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.163', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.164', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.165', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.166', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.167', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.16', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.17', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.171', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.17', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.172', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.17', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.173', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.17', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.174', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.17', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.175', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.17', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.18', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.181', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.182', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.183', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.184', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.185', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.186', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.19', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.18', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.191', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.19', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.7', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.71', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.7', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.72', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.7', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.73', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.7', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.9', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.91', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.9', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.92', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.9', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-023.93', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-023.9', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.33', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-024.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-024.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.14', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.311', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.31', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.312', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.31', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-025.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-025.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.02', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.53', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.54', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.61', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.62', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.9', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-026.91', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-026.9', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-027', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-027.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-027', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-027.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-027', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-027.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-027', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-028.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-028.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.24', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-029.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-029', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-030', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-030.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-030.02', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-030.03', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.41', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.42', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-031.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-031.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-030', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-032.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-032', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.41', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.42', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-033.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-033', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-034', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-035', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-036', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-036.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-036', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-036.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-036', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-036.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-036', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-039', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-039.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-039', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-039.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-039.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-039.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-039.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-039.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-039', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-040', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-040.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.53', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-040', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.61', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-041.62', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-041.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.53', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.7', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.71', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.7', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-042.72', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-042.7', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.61', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.62', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043.6', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-043.7', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-043', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-044.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-044', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.24', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.33', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.6', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-045.7', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-045', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-046.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-046', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-047', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-047.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-047', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-047.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-047', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-047.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-047', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-047.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-047', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-049', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-049.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-049', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-049.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-049.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-049.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-049.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-050', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-050.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-050', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-050.02', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-050', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-050.03', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-050', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-051', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-051.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-051', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-051.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-051', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-051.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-051', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-051.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-051', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.211', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.21', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.212', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.21', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.213', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.21', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.221', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.22', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.222', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.22', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.23', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.24', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.25', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.251', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.25', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-052.252', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-052.25', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-053', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-053', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-053', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-053', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-053.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-053', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-054', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-054.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-054', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-054.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-054', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-059', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-059', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-059', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-059', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-059.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-059', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-060', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-060.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-060', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.011', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.01', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.012', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.01', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.51', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.52', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.5', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.521', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.52', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.522', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.52', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-061.523', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-061.52', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.13', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.21', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.22', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.2', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.41', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-062.42', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-062.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-063', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-063.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-063', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-063.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-063', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-063.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-063', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-064.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-064', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-065', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-065.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-065', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-065.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-065', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-065.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-065', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.41', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.42', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066.4', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.9', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-066.91', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-066.9', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-067', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-069', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069.11', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-069.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069.12', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-069.1', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-069', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-069.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-069', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-070', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-070.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-070', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-071', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-071', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-071', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-071', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-071.5', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-071', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-072', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.2', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.3', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.31', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.32', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.33', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073.3', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-073.4', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-073', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-080', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-090', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-000', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-910', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-910.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-910', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-911', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-912', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-913', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-914', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-915', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-916', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-917', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-918', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-919', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-919.1', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-919', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-920', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-920.01', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-920', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-921', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-922', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-990', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-991', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
        $manager->persist($classificacao);
        
        $classificacao = $this->getReference('Classificacao-992', Classificacao::class);
        $classificacao->setParent($this->getReference('Classificacao-900', Classificacao::class));
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
        return ['prodexec'];
    }
}
