<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadTipoDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumento;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;

/**
 * Class LoadTipoDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ABAIXO_ASSINADO');
        $tipoDocumento->setNome('ABAIXO-ASSINADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REIVINDICAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ABERT_PROC_LICITATORIO');
        $tipoDocumento->setNome('ABERTURA DE PROCEDIMENTO LICITATÓRIO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ACORDAO');
        $tipoDocumento->setNome('ACÓRDÃO');
        $tipoDocumento->setDescricao('EXPRESSA DECISÃO PROFERIDA PELO CONSELHO DIRETOR, NÃO ABRANGIDA PELOS DEMAIS INSTRUMENTOS DELIBERATIVOS ANTERIORES.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ACORDO');
        $tipoDocumento->setNome('ACORDO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE NÍVEL DE SERVIÇO; COLETIVO DE TRABALHO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ACORDO_COOP_TECNICA');
        $tipoDocumento->setNome('ACORDO DE COOPERAÇÃO TÉCNICA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AGENDA');
        $tipoDocumento->setNome('AGENDA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ALEGACOES');
        $tipoDocumento->setNome('ALEGAÇÕES');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: FINAIS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ALVARA');
        $tipoDocumento->setNome('ALVARÁ');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE FUNCIONAMENTO; JUDICIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANAIS');
        $tipoDocumento->setNome('ANAIS');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EVENTOS; DE ENGENHARIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANALISE');
        $tipoDocumento->setNome('ANÁLISE');
        $tipoDocumento->setDescricao('COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: CONTÁBIL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANALISE_RISCOS');
        $tipoDocumento->setNome('ANÁLISE DE RISCOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANEXO');
        $tipoDocumento->setNome('ANEXO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANOTACAO');
        $tipoDocumento->setNome('ANOTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RESPONSABILIDADE TÉCNICA - ART');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ANTEPROJETO');
        $tipoDocumento->setNome('ANTEPROJETO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE LEI');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('APARTADO');
        $tipoDocumento->setNome('APARTADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: SIGILOSO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('APOLICE');
        $tipoDocumento->setNome('APÓLICE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE SEGURO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('APOSTILA');
        $tipoDocumento->setNome('APOSTILA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CURSO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('APRESENTACAO');
        $tipoDocumento->setNome('APRESENTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DO RELATÓRIO; DA ANÁLISE');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATA');
        $tipoDocumento->setNome('ATA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÃO; DE REALIZAÇÃO DE PREGÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATA_REGISTRO_PRECOS');
        $tipoDocumento->setNome('ATA DE REGISTRO DE PREÇOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATA_REUNIAO');
        $tipoDocumento->setNome('ATA DE REUNIÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATESTADO');
        $tipoDocumento->setNome('ATESTADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: MÉDICO; DE COMPARECIMENTO; DE CAPACIDADE TÉCNICA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATEST_CAPAC_TECNICA');
        $tipoDocumento->setNome('ATESTADO DE CAPACIDADE TÉCNICA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ATO');
        $tipoDocumento->setNome('ATO');
        $tipoDocumento->setDescricao('EXPRESSA DECISÃO SOBRE OUTORGA, EXPEDIÇÃO, MODIFICAÇÃO, TRANSFERÊNCIA, PRORROGAÇÃO, ADAPTAÇÃO E EXTINÇÃO DE CONCESSÕES, PERMISSÕES E AUTORIZAÇÕES PARA EXPLORAÇÃO DE SERVIÇOS, USO DE RECURSOS ESCASSOS E EXPLORAÇÃO DE SATÉLITE, E CHAMAMENTO PÚBLICO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AUDIO');
        $tipoDocumento->setNome('ÁUDIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AUTO');
        $tipoDocumento->setNome('AUTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE VISTORIA; DE INFRAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AUTORIZACAO');
        $tipoDocumento->setNome('AUTORIZAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE FUNCIONAMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AVISO');
        $tipoDocumento->setNome('AVISO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RECEBIMENTO; DE SINISTRO; DE FÉRIAS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('AVISO_AUDIÊNCIA_PUBLICA');
        $tipoDocumento->setNome('AVISO DE AUDIÊNCIA PÚBLICA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BALANCETE');
        $tipoDocumento->setNome('BALANCETE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: FINULLCEIRO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BALANCO');
        $tipoDocumento->setNome('BALANÇO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PATRIMONIAL - BP; FINULLCEIRO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BILHETE');
        $tipoDocumento->setNome('BILHETE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PAGAMENTO; DE LOTERIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BOLETIM');
        $tipoDocumento->setNome('BOLETIM');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE OCORRÊNCIA; INFORMATIVO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BOLETIM_CONCESSAO_DIARIAS');
        $tipoDocumento->setNome('BOLETIM DE CONCESSÃO DE DIÁRIAS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('BOLETO');
        $tipoDocumento->setNome('BOLETO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PAGAMENTO; DE COBRANÇA; DE COBRANÇA REGISTRADA; DE COBRANÇA SEM REGISTRO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CALENDARIO');
        $tipoDocumento->setNome('CALENDÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÕES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CANHOTO');
        $tipoDocumento->setNome('CANHOTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EMBARQUE');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CARTA');
        $tipoDocumento->setNome('CARTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: CONVITE');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CARTAO');
        $tipoDocumento->setNome('CARTÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE IDENTIFICAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CARTAZ');
        $tipoDocumento->setNome('CARTAZ');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EVENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CARTEIRA');
        $tipoDocumento->setNome('CARTEIRA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: NACIONAL DE HABILITAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CEDULA');
        $tipoDocumento->setNome('CÉDULA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE IDENTIDADE; DE CRÉDITO BANCÁRIO; DE CRÉDITO COMERCIAL; DE CRÉDITO IMOBILIÁRIO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CERTIDAO');
        $tipoDocumento->setNome('CERTIDÃO');
        $tipoDocumento->setDescricao('COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: DE TEMPO DE SERVIÇO; DE NASCIMENTO; DE CASAMENTO; DE ÓBITO; NEGATIVA DE FALÊNCIA OU CONCORDATA; NEGATIVA DE DÉBITOS TRABALHISTAS; NEGATIVA DE DÉBITOS TRIBUTÁRIOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CERTIFICADO');
        $tipoDocumento->setNome('CERTIFICADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CONCLUSÃO DE CURSO; DE CALIBRAÇÃO DE EQUIPAMENTO; DE MARCA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CHEQUE');
        $tipoDocumento->setNome('CHEQUE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: CAUÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CNH');
        $tipoDocumento->setNome('CNH');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CNPJ');
        $tipoDocumento->setNome('CNPJ');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('COMPROVANTE');
        $tipoDocumento->setNome('COMPROVANTE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE DESPESA; DE RENDIMENTO; DE RESIDÊNCIA; DE MATRÍCULA; DE UNIÃO ESTÁVEL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('COMUNICADO');
        $tipoDocumento->setNome('COMUNICADO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONFIRMACAO');
        $tipoDocumento->setNome('CONFIRMAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PAGAMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONSULTA');
        $tipoDocumento->setNome('CONSULTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PÚBLICA; INTERNA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONTA');
        $tipoDocumento->setNome('CONTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: TELEFÔNICA; DE ÁGUA; DE LUZ');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONTEUDO_MIDIA');
        $tipoDocumento->setNome('CONTEÚDO DE MÍDIA');
        $tipoDocumento->setDescricao('CONTEÚDO EXTRAÍDO DE MÍDIA COMPACTADO EM ARQUIVO ZIP');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONTRACHEQUE');
        $tipoDocumento->setNome('CONTRACHEQUE');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONTRARRAZOES');
        $tipoDocumento->setNome('CONTRARRAZÕES');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: EM RECURSO; EM APELAÇÃO; EM EMBARGOS INFRINGENTES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONTRATO');
        $tipoDocumento->setNome('CONTRATO');
        $tipoDocumento->setDescricao('COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: SOCIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONVENCAO');
        $tipoDocumento->setNome('CONVENÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: COLETIVA DE TRABALHO; INTERNACIONAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONVÊNIO');
        $tipoDocumento->setNome('CONVÊNIO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CONVITE');
        $tipoDocumento->setNome('CONVITE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÃO; PARA EVENTO; DE CASAMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CORRESPONDÊNCIA');
        $tipoDocumento->setNome('CORRESPONDÊNCIA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('COTA');
        $tipoDocumento->setNome('COTA');
        $tipoDocumento->setDescricao('TIPO DE DOCUMENTO PRÓPRIO DA AGU (VIDE ANEXO À PORTARIA 1399/2009 DA AGU).');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('COTACAO');
        $tipoDocumento->setNome('COTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PREÇO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CPF');
        $tipoDocumento->setNome('CPF');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CRACHA');
        $tipoDocumento->setNome('CRACHÁ');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE IDENTIFICAÇÃO; DE EVENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CREDENCIAL');
        $tipoDocumento->setNome('CREDENCIAL');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE SEGURANÇA; DE AGENTE DE FISCALIZAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CRONOGRAMA');
        $tipoDocumento->setNome('CRONOGRAMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PROJETO; DE ESTUDOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CROQUI');
        $tipoDocumento->setNome('CROQUI');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ACESSO, URBANO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('CURRICULO');
        $tipoDocumento->setNome('CURRÍCULO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CANDIDATO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DACON');
        $tipoDocumento->setNome('DACON');
        $tipoDocumento->setDescricao('DEMONSTRATIVO DE APURAÇÃO DE CONTRIBUIÇÕES SOCIAIS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEBÊNTURE');
        $tipoDocumento->setNome('DEBÊNTURE');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DECISAO');
        $tipoDocumento->setNome('DECISÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ADMINISTRATIVA; JUDICIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DECLARACAO');
        $tipoDocumento->setNome('DECLARAÇÃO');
        $tipoDocumento->setDescricao('COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: DE IMPOSTO DE RENDA; DE CONFORMIDADE; DE RESPONSABILIDADE TÉCNICA; DE ACUMULAÇÃO DE APOSENTADORIA; DE ACUMULAÇÃO DE CARGOS; DE INFORMAÇÕES ECONÔMICO-FISCAIS DA PESSOA JURÍDICA (DIPJ)');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DECRETO');
        $tipoDocumento->setNome('DECRETO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEFESA');
        $tipoDocumento->setNome('DEFESA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ADMINISTRATIVA; JUDICIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEGRAVACAO');
        $tipoDocumento->setNome('DEGRAVACÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DELIBERACAO');
        $tipoDocumento->setNome('DELIBERAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RECURSOS; DO CONSELHO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEMONSTRACAO');
        $tipoDocumento->setNome('DEMONSTRAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RESULTADO DO EXERCÍCIO - DRE; DE FLUXO DE CAIXA; FINULLCEIRA; CONTÁBIL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEMONSTRATIVO');
        $tipoDocumento->setNome('DEMONSTRATIVO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: FINULLCEIRO; DE PAGAMENTO; DE ARRECADAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DENUNCIA');
        $tipoDocumento->setNome('DENÚNCIA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DEPOIMENTO');
        $tipoDocumento->setNome('DEPOIMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DAS TESTEMUNHAS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DESPACHO');
        $tipoDocumento->setNome('DESPACHO');
        $tipoDocumento->setDescricao('MANIFESTAÇÃO DE MERO EXPEDIENTE, SEM CUNHO DECISÓRIO, NÃO ABRANGIDA PELOS DEMAIS INSTRUMENTOS DELIBERATIVOS, QUE PROMOVE UMA PROVIDÊNCIA ORDINATÓRIA PROPULSORA DO PROCESSO ADMINISTRATIVO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DESPACHO_AGU');
        $tipoDocumento->setNome('DESPACHO (AGU)');
        $tipoDocumento->setDescricao('TIPO DE DOCUMENTO PRÓPRIO DA AGU (VIDE ANEXO À PORTARIA 1399/2009 DA AGU).');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DESPACHO_DECISORIO');
        $tipoDocumento->setNome('DESPACHO DECISÓRIO');
        $tipoDocumento->setDescricao('EXPRESSA DECISÃO SOBRE MATÉRIAS NÃO ABRANGIDAS PELOS DEMAIS INSTRUMENTOS DELIBERATIVOS.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DIAGNOSTICO');
        $tipoDocumento->setNome('DIAGNÓSTICO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: MÉDICO; DE AUDITORIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DIARIO');
        $tipoDocumento->setNome('DIÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE JUSTIÇA; OFICIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DIPLOMA');
        $tipoDocumento->setNome('DIPLOMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CONCLUSÃO DE CURSO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DIRETRIZ');
        $tipoDocumento->setNome('DIRETRIZ');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ORÇAMENTÁRIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DISSERTACAO');
        $tipoDocumento->setNome('DISSERTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE MESTRADO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DOCUMENTO');
        $tipoDocumento->setNome('DOCUMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE IDENTIFICAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DOD');
        $tipoDocumento->setNome('DOCUMENTO DE OFICIALIZAÇÃO DA DEMANDA (DOD)');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('DOSSIÊ');
        $tipoDocumento->setNome('DOSSIÊ');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PROCESSO; TÉCNICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('E_MAIL');
        $tipoDocumento->setNome('E-MAIL');
        $tipoDocumento->setDescricao('INDICADO NOS PARÂMETROS PARA CORRESPONDER AO ENVIO DE CORRESPONDÊNCIA ELETRÔNICA DO SEI');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EDITAL');
        $tipoDocumento->setNome('EDITAL');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EMBARGOS');
        $tipoDocumento->setNome('EMBARGOS');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE DECLARAÇÃO; DE EXECUÇÃO OU INFRINGENTES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EMENDA');
        $tipoDocumento->setNome('EMENDA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: CONSTITUCIONAL; DE COMISSÃO; DE BANCADA; DE RELATORIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESCALA');
        $tipoDocumento->setNome('ESCALA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE FÉRIAS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESCLARECIMENTO');
        $tipoDocumento->setNome('ESCLARECIMENTO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESCRITURA');
        $tipoDocumento->setNome('ESCRITURA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PÚBLICA; DE IMÓVEL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESCRITURACAO');
        $tipoDocumento->setNome('ESCRITURAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: CONTÁBIL DIGITAL - ECD; FISCAL DIGITAL - EFD; FISCAL DIGITAL - EFD-CONTRIBUIÇÕES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESTATUTO');
        $tipoDocumento->setNome('ESTATUTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: SOCIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESTRATEGIA');
        $tipoDocumento->setNome('ESTRATÉGIA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DA CONTRATAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ESTUDO');
        $tipoDocumento->setNome('ESTUDO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: TÉCNICO PRELIMINAR DA CONTRATAÇÃO; TÉCNICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EST_TEC_PREL_CONTRATACAO');
        $tipoDocumento->setNome('ESTUDO TÉCNICO PRELIMINAR DA CONTRATAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EXAME');
        $tipoDocumento->setNome('EXAME');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: MÉDICO; LABORATORIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EXPOSICAO');
        $tipoDocumento->setNome('EXPOSIÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE MOTIVOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EXPOSICAO_MOTIVOS');
        $tipoDocumento->setNome('EXPOSIÇÃO DE MOTIVOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('EXTRATO');
        $tipoDocumento->setNome('EXTRATO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE SISTEMAS; BANCÁRIO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FATURA');
        $tipoDocumento->setNome('FATURA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FICHA');
        $tipoDocumento->setNome('FICHA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CADASTRO; DE INSCRIÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FLUXOGRAMA');
        $tipoDocumento->setNome('FLUXOGRAMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PROCESSO; DE DOCUMENTOS; DE BLOCOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FOLDER');
        $tipoDocumento->setNome('FOLDER');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EVENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FOLHA');
        $tipoDocumento->setNome('FOLHA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE FREQUÊNCIA DE ESTAGIÁRIO; DE FREQUÊNCIA DE SERVIDOR');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FOLHETO');
        $tipoDocumento->setNome('FOLHETO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EVENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FORMULARIO');
        $tipoDocumento->setNome('FORMULÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CONTATO; DE REVISÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('FORMULARIO_OUVIDORIA');
        $tipoDocumento->setNome('FORMULÁRIO DE OUVIDORIA');
        $tipoDocumento->setDescricao('FORMULÁRIO DE PETICIONAMENTO DA OUVIDORIA, DISPONIBILIZADO NO PORTAL DO ÓRGÃO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('GRACURRICULAR');
        $tipoDocumento->setNome('GRADE CURRICULAR');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DO CURSO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('GUIA');
        $tipoDocumento->setNome('GUIA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RECOLHIMENTO DA UNIÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('HISTORICO');
        $tipoDocumento->setNome('HISTÓRICO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ESCOLAR');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('IMPUGNACAO');
        $tipoDocumento->setNome('IMPUGNAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INDICACAO');
        $tipoDocumento->setNome('INDICAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INDICACAO_INTEGR_TECNICO');
        $tipoDocumento->setNome('INDICAÇÃO DE INTEGRANTE TÉCNICO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INFORMACAO');
        $tipoDocumento->setNome('INFORMAÇÃO');
        $tipoDocumento->setDescricao('TIPO DE DOCUMENTO PRÓPRIO DA AGU (VIDE ANEXO À PORTARIA 1399/2009 DA AGU).');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INFORME');
        $tipoDocumento->setNome('INFORME');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RENDIMENTOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INSCRICAO');
        $tipoDocumento->setNome('INSCRIÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: NO CURSO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INST_EQU_PLANEJ_CONTRAT');
        $tipoDocumento->setNome('INSTITUIÇÃO DA EQUIPE DE PLANEJ. DA CONTRATAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INSTRUCAO');
        $tipoDocumento->setNome('INSTRUÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: NORMATIVA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INTENCAO');
        $tipoDocumento->setNome('INTENÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RECURSO; DE COMPRA; DE VENDA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('INVENTARIO');
        $tipoDocumento->setNome('INVENTÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ESTOQUE; EXTRAJUDICIAL; JUDICIAL; EM CARTÓRIO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LAUDO');
        $tipoDocumento->setNome('LAUDO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: MÉDICO; CONCLUSIVO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LEI');
        $tipoDocumento->setNome('LEI');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: COMPLEMENTAR');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LICENCA');
        $tipoDocumento->setNome('LICENÇA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ESTAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LISTA');
        $tipoDocumento->setNome('LISTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PRESENÇA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LISTAGEM');
        $tipoDocumento->setNome('LISTAGEM');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('LIVRO');
        $tipoDocumento->setNome('LIVRO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: CAIXA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MANDADO');
        $tipoDocumento->setNome('MANDADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE BUSCA E APREENSÃO; DE CITAÇÃO; DE INTIMAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MANIFESTO');
        $tipoDocumento->setNome('MANIFESTO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MANUAL');
        $tipoDocumento->setNome('MANUAL');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DO USUÁRIO; DO SISTEMA; DO EQUIPAMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MAPA');
        $tipoDocumento->setNome('MAPA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RUAS; DE RISCO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MATERIA');
        $tipoDocumento->setNome('MATÉRIA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PARA APRECIAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MATERIAL');
        $tipoDocumento->setNome('MATERIAL');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PUBLICITÁRIO; DE EVENTO; DE PROMOÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MEDIDA_PROVISORIA');
        $tipoDocumento->setNome('MEDIDA PROVISÓRIA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MEMORANDO');
        $tipoDocumento->setNome('MEMORANDO');
        $tipoDocumento->setDescricao('COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: DE ENTENDIMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MEMORANDO_CIRCULAR');
        $tipoDocumento->setNome('MEMORANDO-CIRCULAR');
        $tipoDocumento->setDescricao('MESMA DEFINIÇÃO DO MEMORANDO COM APENAS UMA DIFERENÇA: É ENCAMINHADO SIMULTANEAMENTE A MAIS DE UM CARGO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MEMORIA');
        $tipoDocumento->setNome('MEMÓRIA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CÁLCULO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MEMORIAL');
        $tipoDocumento->setNome('MEMORIAL');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DESCRITIVO; DE INCORPORAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MENSAGEM');
        $tipoDocumento->setNome('MENSAGEM');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ANIVERSÁRIO; DE BOAS VINDAS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA');
        $tipoDocumento->setNome('MINUTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PORTARIA; DE RESOLUÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MIN_ACORDO_COOP_TEC');
        $tipoDocumento->setNome('MINUTA DE ACORDO DE COOPERAÇÃO TÉCNICA');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MIN_ATA_REGISTRO_PRECOS');
        $tipoDocumento->setNome('MINUTA DE ATA DE REGISTRO DE PREÇOS');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_CONTRATO');
        $tipoDocumento->setNome('MINUTA DE CONTRATO');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_CONVÊNIO');
        $tipoDocumento->setNome('MINUTA DE CONVÊNIO');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_EDITAL');
        $tipoDocumento->setNome('MINUTA DE EDITAL');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_PORTARIA');
        $tipoDocumento->setNome('MINUTA DE PORTARIA');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_PROJETO_BASICO');
        $tipoDocumento->setNome('MINUTA DE PROJETO BÁSICO');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_RESOLUCAO');
        $tipoDocumento->setNome('MINUTA DE RESOLUÇÃO');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MINUTA_TERMO_ADITIVO');
        $tipoDocumento->setNome('MINUTA DE TERMO ADITIVO');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MIN_TERMO_RESC_AMIGAVEL');
        $tipoDocumento->setNome('MINUTA DE TERMO DE RESCISÃO AMIGÁVEL');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MIN_TERMO_RESC_UNILAT');
        $tipoDocumento->setNome('MINUTA DE TERMO DE RESCISÃO UNILATERAL');
        $tipoDocumento->setDescricao('TIPO PRÓPRIO PARA FORMALIZAÇÃO DE MINUTAS DESTE TIPO DE DOCUMENTO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MOCAO');
        $tipoDocumento->setNome('MOÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE APOIO; DE PESAR; DE REPÚDIO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('MOVIMENTACAO');
        $tipoDocumento->setNome('MOVIMENTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE BENS MÓVEIS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('NORMA');
        $tipoDocumento->setNome('NORMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: TÉCNICA; DE CONDUTA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('NOTA');
        $tipoDocumento->setNome('NOTA');
        $tipoDocumento->setDescricao('TIPO DE DOCUMENTO PRÓPRIO DA AGU (VIDE ANEXO À PORTARIA 1399/2009 DA AGU). - COMO DOCUMENTO EXTERNO PODE SER COMPLEMENTADO: FISCAL; INFORMATIVA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('NOTA_TECNICA');
        $tipoDocumento->setNome('NOTA TÉCNICA');
        $tipoDocumento->setDescricao('INSTRUMENTO DE COMUNICAÇÃO INTERNA OU EXTERNA QUE EXPRESSA UM JUÍZO OU OPINIÃO SOBRE QUESTÃO TÉCNICA, COM VISTAS A ESCLARECER DÚVIDAS OU INDAGAÇÕES.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('NOTIFICACAO');
        $tipoDocumento->setNome('NOTIFICAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('OFICIO');
        $tipoDocumento->setNome('OFÍCIO');
        $tipoDocumento->setDescricao('MODALIDADES DE COMUNICAÇÃO OFICIAL. É EXPEDIDO PARA E PELAS AUTORIDADES. TEM COMO FINALIDADE O TRATAMENTO DE ASSUNTOS OFICIAIS PELOS ÓRGÃOS DA ADMINISTRAÇÃO PÚBLICA ENTRE SI E TAMBÉM COM PARTICULARES.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('OFICIO_CIRCULAR');
        $tipoDocumento->setNome('OFÍCIO-CIRCULAR');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ORCAMENTO');
        $tipoDocumento->setNome('ORÇAMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE OBRA; DE SERVIÇO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ORDEM');
        $tipoDocumento->setNome('ORDEM');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE SERVIÇO; DE COMPRA; DO DIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ORDEM_SERV_FORN_BENS');
        $tipoDocumento->setNome('ORDEM DE SERVIÇO OU DE FORNECIMENTO DE BENS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ORGANOGRAMA');
        $tipoDocumento->setNome('ORGANOGRAMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DA EMPRESA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ORIENTACAO');
        $tipoDocumento->setNome('ORIENTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: NORMATIVA; JURISPRUDENCIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PAGINA');
        $tipoDocumento->setNome('PÁGINA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DO DIÁRIO OFICIAL DA UNIÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PANFLETO');
        $tipoDocumento->setNome('PANFLETO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PROMOÇÃO; DE EVENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PARECER');
        $tipoDocumento->setNome('PARECER');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PARECER_AGU');
        $tipoDocumento->setNome('PARECER (AGU)');
        $tipoDocumento->setDescricao('TIPO DE DOCUMENTO PRÓPRIO DA AGU (VIDE ANEXO À PORTARIA 1399/2009 DA AGU).');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PASSAPORTE');
        $tipoDocumento->setNome('PASSAPORTE');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PAUTA');
        $tipoDocumento->setNome('PAUTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE JULGAMENTOS; DE AUDIÊNCIAS; DAS SEÇÕES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PEDIDO');
        $tipoDocumento->setNome('PEDIDO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RECONSIDERAÇÃO; DE ESCLARECIMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PETICAO');
        $tipoDocumento->setNome('PETIÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: INICIAL; INCIDENTAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PLANILHA');
        $tipoDocumento->setNome('PLANILHA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CUSTOS E FORMAÇÃO DE PREÇOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PLANO');
        $tipoDocumento->setNome('PLANO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE SERVIÇO; DE CONTAS CONTÁBIL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PLANO_FISCALIZACAO');
        $tipoDocumento->setNome('PLANO DE FISCALIZAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PGP');
        $tipoDocumento->setNome('PLANO DE GERENCIAMENTO DE PROJETO (PGP)');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PLANO_INSERCAO');
        $tipoDocumento->setNome('PLANO DE INSERÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PLANTA');
        $tipoDocumento->setNome('PLANTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: BAIXA; DE LOCALIZAÇÃO; DE SITUAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PORTARIA');
        $tipoDocumento->setNome('PORTARIA');
        $tipoDocumento->setDescricao('EXPRESSA DECISÃO RELATIVA A ASSUNTOS DE INTERESSE INTERNO DA AGÊNCIA.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PRECATORIO');
        $tipoDocumento->setNome('PRECATÓRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ALIMENTAR; FEDERAL; ESTADUAL; MUNICIPAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PREST_CONTAS_SUPR_FUNDOS');
        $tipoDocumento->setNome('PRESTAÇÃO DE CONTAS DE SUPRIMENTO DE FUNDOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROCESSO');
        $tipoDocumento->setNome('PROCESSO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROCURACAO');
        $tipoDocumento->setNome('PROCURAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROGRAMA');
        $tipoDocumento->setNome('PROGRAMA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE GOVERNO; DE MELHORIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROJETO');
        $tipoDocumento->setNome('PROJETO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: TÉCNICO; COMERCIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROJETO_BASICO');
        $tipoDocumento->setNome('PROJETO BÁSICO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PRONTUARIO');
        $tipoDocumento->setNome('PRONTUÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: MÉDICO; ODONTOLÓGICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PRONUNCIAMENTO');
        $tipoDocumento->setNome('PRONUNCIAMENTO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROPOSTA');
        $tipoDocumento->setNome('PROPOSTA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: COMERCIAL; DE ORÇAMENTO; TÉCNICA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROP_CONC_SUPR_FUNDOS');
        $tipoDocumento->setNome('PROPOSTA DE CONCESSÃO DE SUPRIMENTO DE FUNDOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROSPECTO');
        $tipoDocumento->setNome('PROSPECTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE FUNDOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROTOCOLO');
        $tipoDocumento->setNome('PROTOCOLO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ENTENDIMENTOS; DE ENTREGA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PROVA');
        $tipoDocumento->setNome('PROVA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CONCEITO; DE PROFICIÊNCIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('PUBLICACAO');
        $tipoDocumento->setNome('PUBLICAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: NO DOU; EM JORNAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('QUESTIONARIO');
        $tipoDocumento->setNome('QUESTIONÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE AVALIAÇÃO; DE PESQUISA; SOCIOECONÔMICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECEITA');
        $tipoDocumento->setNome('RECEITA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECIBO');
        $tipoDocumento->setNome('RECIBO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PAGAMENTO; DE ENTREGA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECLAMACAO');
        $tipoDocumento->setNome('RECLAMAÇÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECON_RAT_DISPENSA');
        $tipoDocumento->setNome('RECONHECIMENTO E RATIFICAÇÃO DE DISPENSA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECON_RAT_INEXIGIB');
        $tipoDocumento->setNome('RECONHECIMENTO E RATIFICAÇÃO DE INEXIGIBILIDADE');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RECURSO');
        $tipoDocumento->setNome('RECURSO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ADMINISTRATIVO; JUDICIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REFERENDO');
        $tipoDocumento->setNome('REFERENDO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REGIMENTO');
        $tipoDocumento->setNome('REGIMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: INTERNO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REGISTRO');
        $tipoDocumento->setNome('REGISTRO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE DETALHES DE CHAMADAS - CDR; DE ACESSO; COMERCIAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REGISTRO_REUNIAO');
        $tipoDocumento->setNome('REGISTRO DE REUNIÃO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REGULAMENTO');
        $tipoDocumento->setNome('REGULAMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: GERAL; DISCIPLINAR; DE ADMINISTRAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RELACAO');
        $tipoDocumento->setNome('RELAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE BENS REVERSÍVEIS - RBR');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RELATORIO');
        $tipoDocumento->setNome('RELATÓRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE CONFORMIDADE; DE MEDIÇÕES; DE PRESTAÇÃO DE CONTAS; DE VIAGEM A SERVIÇO; FOTOGRÁFICO; TÉCNICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RELAT_CONC_SUPR_FUNDOS');
        $tipoDocumento->setNome('RELATÓRIO DE CONCESSÃO DE SUPRIMENTO DE FUNDOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RELAT_VIAGEM_SERVICO');
        $tipoDocumento->setNome('RELATÓRIO DE VIAGEM A SERVIÇO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RELEASE');
        $tipoDocumento->setNome('RELEASE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE RESULTADOS; DE PRODUTOS; DE SERVIÇOS');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REPRESENTACAO');
        $tipoDocumento->setNome('REPRESENTAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: COMERCIAL; PROCESSUAL; FISCAL');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REQUERIMENTO');
        $tipoDocumento->setNome('REQUERIMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: ADMINISTRATIVO; DE ADAPTAÇÃO; DE ALTERAÇÃO TÉCNICA; DE ALTERAÇÃO TÉCNICA; DE AUTOCADASTRAMENTO DE ESTAÇÃO; DE LICENCIAMENTO DE ESTAÇÃO; DE SERVIÇO DE TELECOMUNICAÇÕES');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('REQUISICAO');
        $tipoDocumento->setNome('REQUISIÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE AUDITORIA; DE EXCLUSÃO; DE SEGUNDA VIA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RESOLUCAO');
        $tipoDocumento->setNome('RESOLUÇÃO');
        $tipoDocumento->setDescricao('ATO NORMATIVO COM EFEITOS INTERNOS AO ÓRGÃO QUE A CRIOU OU A OUTROS ÓRGÃOS SUBORDINADOS.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RESULTADO');
        $tipoDocumento->setNome('RESULTADO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE EXAME MÉDICO; DE CONTESTAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RESUMO');
        $tipoDocumento->setNome('RESUMO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: TÉCNICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('RG');
        $tipoDocumento->setNome('RG');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('ROTEIRO');
        $tipoDocumento->setNome('ROTEIRO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE INSTALAÇÃO; DE INSPEÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SENTENCA');
        $tipoDocumento->setNome('SENTENÇA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE MÉRITO; TERMINATIVA; DECLARATÓRIA; CONSTITUTIVA; CONDENATÓRIA; MANDAMENTAL; EXECUTIVA');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SINOPSE');
        $tipoDocumento->setNome('SINOPSE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DO LIVRO; DO ESTUDO TÉCNICO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SOLICITACAO');
        $tipoDocumento->setNome('SOLICITAÇÃO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE PAGAMENTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SOLICIT_DESP_SUPR_FUNDOS');
        $tipoDocumento->setNome('SOLICITAÇÃO DE DESPESAS POR SUPRIMENTO DE FUNDOS');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SUMARIO');
        $tipoDocumento->setNome('SUMÁRIO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: EXECUTIVO, DE EDIÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('SUMULA');
        $tipoDocumento->setNome('SÚMULA');
        $tipoDocumento->setDescricao('EXPRESSA DECISÃO QUANTO À INTERPRETAÇÃO DA LEGISLAÇÃO E FIXA ENTENDIMENTO SOBRE MATÉRIAS DE COMPETÊNCIA DO ÓRGÃO, COM EFEITO VINCULATIVO.');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TABELA');
        $tipoDocumento->setNome('TABELA');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE VISTO; DE PASSAPORTE; DE CERTIDÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TELEGRAMA');
        $tipoDocumento->setNome('TELEGRAMA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO');
        $tipoDocumento->setNome('TERMO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE OPÇÃO POR AUXÍLIO FINULLCEIRO; DE OPÇÃO PARA CONTRIBUIÇÃO AO CPSS; DE CONCILIAÇÃO; DE DEVOLUÇÃO; DE DOAÇÃO; DE RECEBIMENTO; DE RESCISÃO; DE COMPROMISSO DE ESTÁGIO; DE REPRESENTAÇÃO; DE RESPONSABILIDADE DE INSTALAÇÃO - TRI');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_ADITIVO');
        $tipoDocumento->setNome('TERMO ADITIVO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TAP');
        $tipoDocumento->setNome('TERMO DE ABERTURA DE PROJETO (TAP)');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_APOSTILAMENTO');
        $tipoDocumento->setNome('TERMO DE APOSTILAMENTO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_CIENC_MAN_SIGILO');
        $tipoDocumento->setNome('TERMO DE CIÊNCIA DE MANUTENÇÃO DE SIGILO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_COMP_MAN_SIGLO_CONT');
        $tipoDocumento->setNome('TERMO DE COMPROM. MANUTENÇÃO DE SIGILO EM CONTRATO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_ENCERR_CONTRATO');
        $tipoDocumento->setNome('TERMO DE ENCERRAMENTO DE CONTRATO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERM_ENCERR_TRAM_FISICO');
        $tipoDocumento->setNome('TERMO DE ENCERRAMENTO DE TRÂMITE FÍSICO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_RECEB_DEF');
        $tipoDocumento->setNome('TERMO DE RECEBIMENTO DEFINITIVO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_RECEB_PROV');
        $tipoDocumento->setNome('TERMO DE RECEBIMENTO PROVISÓRIO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_REFERÊNCIA');
        $tipoDocumento->setNome('TERMO DE REFERÊNCIA');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_RESCISAO_AMIGAVEL');
        $tipoDocumento->setNome('TERMO DE RESCISÃO AMIGÁVEL');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TERMO_RESCISAO_UNILATERAL');
        $tipoDocumento->setNome('TERMO DE RESCISÃO UNILATERAL');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TESE');
        $tipoDocumento->setNome('TESE');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE DOUTORADO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TESTAMENTO');
        $tipoDocumento->setNome('TESTAMENTO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: PARTICULAR; VITAL; CERRADO; CONJUNTIVO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('TITULO');
        $tipoDocumento->setNome('TÍTULO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE ELEITOR; PÚBLICO; DE CAPITALIZAÇÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('VIDEO');
        $tipoDocumento->setNome('VÍDEO');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE REUNIÃO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('VOLUME');
        $tipoDocumento->setNome('VOLUME');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('VOTO');
        $tipoDocumento->setNome('VOTO');
        $tipoDocumento->setDescricao('');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento($this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class));
        $tipoDocumento->setSigla('VOUCHER');
        $tipoDocumento->setNome('VOUCHER');
        $tipoDocumento->setDescricao('PODENDO SER COMPLEMENTADO: DE DESCONTO');
        $tipoDocumento->setAtivo(true);
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);
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
        return 3;
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
