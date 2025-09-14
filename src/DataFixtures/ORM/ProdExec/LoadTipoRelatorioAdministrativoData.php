<?php

/** @noinspection ProblematicWhitespace */

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadTipoRelatorioAdministrativoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelatorio;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;

/**
 * Class LoadTipoRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoRelatorioAdministrativoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Adicionar novos tipos no final da listagem

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TRABALHO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('AFASTAMENTOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('AFASTAMENTOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, u.nome as usuario, ma.valor as modalidade, a.dataInicio as inicio, a.dataFim as final from SuppCore\AdministrativoBackend\Entity\Afastamento a LEFT JOIN a.colaborador c LEFT JOIN c.usuario u LEFT JOIN a.modalidadeAfastamento ma INNER JOIN c.lotacoes l INNER JOIN l.setor s WHERE a.dataInicio >= :dataHoraInicio AND a.dataFim <= :dataHoraFim AND s.id = :setor ORDER BY u.nome ASC, ma.valor ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TRABALHO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('AFASTAMENTOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('AFASTAMENTOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, s.nome, u.nome as usuario, ma.valor as modalidade, a.dataInicio as inicio, a.dataFim as final from SuppCore\AdministrativoBackend\Entity\Afastamento a LEFT JOIN a.colaborador c LEFT JOIN c.usuario u LEFT JOIN a.modalidadeAfastamento ma INNER JOIN c.lotacoes l INNER JOIN l.setor s INNER JOIN s.unidade un WHERE a.dataInicio >= :dataHoraInicio AND a.dataFim <= :dataHoraFim AND un.id = :unidade ORDER BY s.nome ASC, u.nome ASC, ma.valor ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, p.NUP, t.id as tarefa_id, u.nome as usuario, e.nome as especie, a.criadoEm as dia_hora from SuppCore\AdministrativoBackend\Entity\Atividade a  INNER JOIN a.tarefa t LEFT JOIN a.especieAtividade e LEFT JOIN a.usuario u INNER JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND s.id = :setor ORDER BY u.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(a.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Atividade a LEFT JOIN a.tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN a.usuario u WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, p.NUP, t.id as tarefa_id, s.nome as setor, u.nome as usuario, e.nome as especie, a.criadoEm as dia_hora from SuppCore\AdministrativoBackend\Entity\Atividade a  LEFT JOIN a.tarefa t LEFT JOIN a.especieAtividade e LEFT JOIN a.criadoPor u LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade un LEFT JOIN t.processo p WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND un.id = :unidade ORDER BY s.nome ASC, u.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(a.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Atividade a LEFT JOIN a.tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade un LEFT JOIN a.criadoPor u WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND un.id = :unidade GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, p.NUP, t.id as tarefa_id, s.sigla as setor, e.nome as especie, a.criadoEm as dia_hora from SuppCore\AdministrativoBackend\Entity\Atividade a  LEFT JOIN a.tarefa t LEFT JOIN a.especieAtividade e LEFT JOIN a.usuario u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('ATIVIDADES LANÇADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('ATIVIDADES LANÇADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(a.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Atividade a LEFT JOIN a.tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN a.usuario u WHERE a.criadoEm >= :dataHoraInicio AND a.criadoEm <= :dataHoraFim AND u.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES EXTERNAS REMETIDAS EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES EXTERNAS REMETIDAS EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, pd.nome as destinatario, ec.nome as especie, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.especieDocumentoAvulso ec INNER JOIN c.setorOrigem so INNER JOIN so.unidade un LEFT JOIN c.pessoaDestino pd LEFT JOIN c.processo p WHERE c.dataHoraRemessa >= :dataHoraInicio AND c.dataHoraRemessa <= :dataHoraFim AND un.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TRABALHO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES INTERNAS RECEBIDAS E PENDENTES DE RESPOSTA EM DIAS EM UMA UNIDADE');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES INTERNAS RECEBIDAS E PENDENTES DE RESPOSTA EM DIAS EM UMA UNIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP as nup_origem, pd.NUP as nup_destino, un.nome as remetente, ec.nome as especie, c.dataHoraRemessa as remessa, DATE_DIFF(CURRENT_DATE(), c.dataHoraRemessa) as dias_pendente FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.especieDocumentoAvulso ec LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un INNER JOIN c.setorDestino d LEFT JOIN c.processo p LEFT JOIN c.processoDestino pd WHERE c.dataHoraRemessa IS NOT NULL  AND c.dataHoraResposta IS NULL AND d.id = :unidade ORDER BY p.NUP');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES INTERNAS RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES INTERNAS RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, un.nome as remetente, ec.nome as especie, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.especieDocumentoAvulso ec LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un INNER JOIN c.setorDestino d LEFT JOIN c.processo p WHERE c.dataHoraRemessa >= :dataHoraInicio AND c.dataHoraRemessa <= :dataHoraFim AND d.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES INTERNAS RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES INTERNAS RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT un.nome, count(c.id) as subtotal from SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un INNER JOIN c.setorDestino d WHERE c.dataHoraRemessa >= :dataHoraInicio AND c.dataHoraRemessa <= :dataHoraFim AND d.id = :unidade GROUP BY un.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES INTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES INTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, d.nome as destinatario, ec.nome as especie, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.especieDocumentoAvulso ec INNER JOIN c.setorOrigem so INNER JOIN so.unidade un LEFT JOIN c.setorDestino d LEFT JOIN c.processo p WHERE c.dataHoraRemessa >= :dataHoraInicio AND c.dataHoraRemessa <= :dataHoraFim AND un.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES INTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES INTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT so.nome, count(c.id) as subtotal from SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un WHERE c.dataHoraRemessa >= :dataHoraInicio AND c.dataHoraRemessa <= :dataHoraFim AND un.id = :unidade GROUP BY so.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES PENDENTES DE RESPOSTA ENVIADAS POR UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES PENDENTES DE RESPOSTA ENVIADAS POR UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, CONCAT(d.nome, CONCAT(CONCAT(sd.nome, \'/\'), ud.sigla)) as destinatario, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN c.pessoaDestino d LEFT JOIN c.setorDestino sd LEFT JOIN sd.unidade ud LEFT JOIN c.processo p WHERE c.dataHoraResposta IS NULL AND c.dataHoraRemessa IS NOT NULL AND so.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES PENDENTES DE RESPOSTA ENVIADAS POR UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES PENDENTES DE RESPOSTA ENVIADAS POR UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, CONCAT(d.nome, CONCAT(CONCAT(sd.nome, \'/\'), ud.sigla)) as destinatario, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un LEFT JOIN c.pessoaDestino d LEFT JOIN c.setorDestino sd LEFT JOIN sd.unidade ud LEFT JOIN c.processo p WHERE c.dataHoraResposta IS NULL AND c.dataHoraRemessa IS NOT NULL AND un.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES PENDENTES DE RESPOSTA POR UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES PENDENTES DE RESPOSTA POR UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, CONCAT(d.nome, CONCAT(CONCAT(sd.nome, \'/\'), ud.sigla)) as destinatario, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un LEFT JOIN c.pessoaDestino d LEFT JOIN c.setorDestino sd LEFT JOIN sd.unidade ud LEFT JOIN c.processo p WHERE c.dataHoraResposta IS NULL AND c.dataHoraRemessa IS NOT NULL AND ud.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TRABALHO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES REMETIDAS E PENDENTES DE RESPOSTA EM DIAS ENVIADAS POR UMA UNIDADE');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES REMETIDAS E PENDENTES DE RESPOSTA EM DIAS ENVIADAS POR UMA UNIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, CONCAT(d.nome, CONCAT(CONCAT(sd.nome, \'/\'), ud.sigla)) as destinatario, ec.nome as especie, c.dataHoraRemessa as remessa, DATE_DIFF(CURRENT_DATE(), c.dataHoraRemessa) as dias_pendente FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.especieDocumentoAvulso ec INNER JOIN c.setorOrigem so INNER JOIN so.unidade un LEFT JOIN c.pessoaDestino d LEFT JOIN c.setorDestino sd LEFT JOIN sd.unidade ud LEFT JOIN c.processo p WHERE c.dataHoraRemessa IS NOT NULL AND c.dataHoraResposta IS NULL AND un.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-ATIVIDADES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('DISTRIBUIÇÃO DIÁRIA DE TAREFAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('DISTRIBUIÇÃO DIÁRIA DE TAREFAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('dataHoraInicio,dataHoraFim,setor');
        $tipoRelatorio->setDQL('SELECT d.usuarioPosterior FROM SuppCore\AdministrativoBackend\Entity\Distribuicao d INNER JOIN d.usuarioPosterior usr WHERE d.criadoEm >= :dataHoraInicio AND d.criadoEm <= :dataHoraFim AND d.setorPosterior = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-RECURSOS HUMANOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('FOLHA DE REGISTRO DE ATIVIDADES');
        $tipoRelatorio->setDescricao('FOLHA DE REGISTRO DE ATIVIDADES');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/registroAtividades.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT a.id, a.dataHoraConclusao as data_hora_conclusao  FROM SuppCore\AdministrativoBackend\Entity\Atividade a  INNER JOIN a.usuario u WHERE a.dataHoraConclusao >= :dataHoraInicio AND a.dataHoraConclusao <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND s.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s LEFT JOIN s.unidade u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s LEFT JOIN s.unidade u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :unidade GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.criadoPor u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS CADASTRADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS CADASTRADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.criadoPor u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :usuario GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS EM UM SETOR HÁ MAIS DE 10 DIAS SEM TAREFAS ABERTAS');
        $tipoRelatorio->setDescricao('NUPS EM UM SETOR HÁ MAIS DE 10 DIAS SEM TAREFAS ABERTAS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP FROM SuppCore\AdministrativoBackend\Entity\Processo p INNER JOIN p.setorAtual s WITH s.id = :setor WHERE p.dataHoraEncerramento IS NULL AND NOT EXISTS (SELECT ext.id FROM SuppCore\AdministrativoBackend\Entity\Tarefa ext INNER JOIN ext.processo exp WHERE exp.id = p.id AND ext.dataHoraConclusaoPrazo IS NULL) AND ((SELECT MAX(t.dataHoraConclusaoPrazo) FROM SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.processo tp WHERE tp.id = p.id) < DATE_SUB(CURRENT_DATE(), 10, \'day\')) ORDER BY p.id ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('PROCESSOS ACOMPANHADOS POR USUÁRIO (DETALHADO)');
        $tipoRelatorio->setDescricao('PROCESSOS ACOMPANHADOS POR USUÁRIO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario');
        $tipoRelatorio->setDQL('SELECT p.NUP, us.nome as nome, s.nome as SETOR, s.sigla 
                from SuppCore\AdministrativoBackend\Entity\Processo p 
                LEFT JOIN p.setorAtual s 
                LEFT JOIN s.lotacoes l
                LEFT JOIN l.colaborador cb
                LEFT JOIN cb.usuario us
                WHERE  us.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-USUÁRIOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ADMINISTRADORES DO SUPP');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ADMINISTRADORES DO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT u.nome, u.email, un.nome as unidade, m.valor as orgao_central FROM SuppCore\AdministrativoBackend\Entity\Usuario u INNER JOIN u.vinculacoesRoles r INNER JOIN u.colaborador c INNER JOIN c.lotacoes l LEFT JOIN  l.setor s LEFT JOIN  s.unidade un LEFT JOIN  un.modalidadeOrgaoCentral m WHERE r.role LIKE \'%ROLE_ADMIN%\' AND u.enabled = 1 AND l.apagadoEm IS NULL GROUP BY u.nome, u.email, un.nome, m.valor ORDER BY m.valor, un.nome, u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-USUÁRIOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ARQUIVISTAS DO SUPP');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ARQUIVISTAS DO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT u.nome, u.email, un.nome as unidade, m.valor as orgao_central FROM SuppCore\AdministrativoBackend\Entity\Usuario u INNER JOIN u.vinculacoesRoles r INNER JOIN u.colaborador c INNER JOIN c.lotacoes l LEFT JOIN  l.setor s LEFT JOIN  s.unidade un LEFT JOIN  un.modalidadeOrgaoCentral m WHERE r.role LIKE \'%ROLE_ARQUIVISTA%\' AND u.enabled = 1 AND l.apagadoEm IS NULL GROUP BY u.nome, u.email, un.nome, m.valor ORDER BY m.valor, un.nome, u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ASSUNTOS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ASSUNTOS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT a.id, a.nome, p.id as pai_id, a.ativo from SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo a LEFT JOIN a.parent p ORDER BY a.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE CLASSIFICAÇÕES');
        $tipoRelatorio->setDescricao('RELAÇÃO DE CLASSIFICAÇÕES');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT c.id, c.nome, p.id as pai_id, c.ativo from SuppCore\AdministrativoBackend\Entity\Classificacao c LEFT JOIN c.parent p ORDER BY c.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-USUÁRIOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE COORDENADORES NACIONAIS DO SUPP');
        $tipoRelatorio->setDescricao('RELAÇÃO DE COORDENADORES NACIONAIS DO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT u.nome, u.email, un.nome as unidade, m.valor as orgao_central FROM SuppCore\AdministrativoBackend\Entity\Usuario u INNER JOIN u.vinculacoesRoles r INNER JOIN u.colaborador c INNER JOIN c.lotacoes l LEFT JOIN  l.setor s LEFT JOIN  s.unidade un LEFT JOIN  un.modalidadeOrgaoCentral m WHERE r.role LIKE \'%ROLE_COORDENADOR_NACIONAL%\' AND u.enabled = 1 AND l.apagadoEm IS NULL GROUP BY u.nome, u.email, un.nome, m.valor ORDER BY m.valor, un.nome, u.nome, m.valor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ESPÉCIES DE ATIVIDADES');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ESPÉCIES DE ATIVIDADES');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT e.id, e.nome, g.nome as genero, e.ativo from SuppCore\AdministrativoBackend\Entity\EspecieAtividade e LEFT JOIN e.generoAtividade g ORDER BY g.nome ASC, e.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ESPÉCIES DE RELEVÂNCIA');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ESPÉCIES DE RELEVÂNCIA');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT e.id, e.nome, g.nome as genero, e.ativo from SuppCore\AdministrativoBackend\Entity\EspecieRelevancia e LEFT JOIN e.generoRelevancia g ORDER BY g.nome ASC, e.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ÓRGÃOS INTERESSADOS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ÓRGÃOS INTERESSADOS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT p.id, p.nome from SuppCore\AdministrativoBackend\Entity\Pessoa p WHERE p.pessoaInteressada = 1 ORDER BY p.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE PROTOCOLOS SEM USUÁRIOS LOTADOS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE PROTOCOLOS SEM USUÁRIOS LOTADOS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT s.id, m.valor, u.nome, u.apenasProtocolo as trancado_tarefas from SuppCore\AdministrativoBackend\Entity\Setor s 
INNER JOIN s.unidade u
INNER JOIN s.especieSetor es
INNER JOIN u.modalidadeOrgaoCentral m 
WHERE es.nome = \'PROTOCOLO\' 
AND u.ativo = 1
AND NOT EXISTS (
   SELECT l.id FROM SuppCore\AdministrativoBackend\Entity\Lotacao l
   INNER JOIN l.setor se
   WHERE se.id = s.id
   AND l.apagadoEm IS NULL
)
ORDER BY m.valor ASC, u.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE SETORES DE UMA UNIDADE');
        $tipoRelatorio->setDescricao('RELAÇÃO DE SETORES DE UMA UNIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT s.id, s.nome, s.sigla, es.nome as especie, s.ativo from SuppCore\AdministrativoBackend\Entity\Setor s LEFT JOIN s.especieSetor es INNER JOIN s.unidade u WHERE s.parent IS NOT NULL AND u.id = :unidade ORDER BY s.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE TIPOS DE DOCUMENTOS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE TIPOS DE DOCUMENTOS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT td.id, td.nome, ed.nome as especie, td.ativo from SuppCore\AdministrativoBackend\Entity\TipoDocumento td LEFT JOIN td.especieDocumento ed ORDER BY ed.nome ASC, td.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE UNIDADES');
        $tipoRelatorio->setDescricao('RELAÇÃO DE UNIDADES');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT s.id, m.valor, s.nome, s.sigla, s.prefixoNUP as nup, s.ativo from SuppCore\AdministrativoBackend\Entity\Setor s LEFT JOIN s.modalidadeOrgaoCentral m WHERE s.parent IS NULL ORDER BY m.valor ASC, s.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE UNIDADES EM IMPLANTAÇÃO');
        $tipoRelatorio->setDescricao('RELAÇÃO DE UNIDADES EM IMPLANTAÇÃO, OU SEJA, QUE ESTÃO ATIVAS MAS AINDA NÃO BLOQUEARAM O AGUDOC E NÃO PODEM GERAR NUPS NOVOS NO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT s.id, s.nome, s.sigla, s.prefixoNUP as nup from SuppCore\AdministrativoBackend\Entity\Setor s WHERE s.parent IS NULL AND s.sequenciaInicialNUP = 0 AND s.ativo = 1 ORDER BY s.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE UNIDADES IMPLANTADAS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE UNIDADES IMPLANTADAS, OU SEJA, QUE ESTÃO ATIVAS E QUE JÁ BLOQUEARAM O AGUDOC, PODENDO GERAR NUPS NOVOS NO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT s.id, s.nome, s.sigla, s.prefixoNUP as nup from SuppCore\AdministrativoBackend\Entity\Setor s WHERE s.parent IS NULL AND s.sequenciaInicialNUP > 0 AND s.ativo = 1 ORDER BY s.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-USUÁRIOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE USUÁRIOS EXTERNOS E PESSOAS CONVENIADAS DO SUPP');
        $tipoRelatorio->setDescricao('RELAÇÃO DE USUÁRIOS EXTERNOS E PESSOAS CONVENIADAS DO SUPP');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT u.nome as usuario, u.email, p.nome as pessoa FROM SuppCore\AdministrativoBackend\Entity\Usuario u INNER JOIN u.vinculacoesPessoasUsuarios vup INNER JOIN vup.pessoa p WHERE u.colaborador IS NULL AND u.enabled = 1 ORDER BY u.nome, p.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-USUÁRIOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE USUÁRIOS LOTADOS EM UMA UNIDADE');
        $tipoRelatorio->setDescricao('RELAÇÃO DE USUÁRIOS LOTADOS EM UMA UNIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT u.nome, u.email, ca.nome as cargo, s.nome as setor, l.distribuidor, l.peso FROM SuppCore\AdministrativoBackend\Entity\Lotacao l INNER JOIN l.colaborador c LEFT JOIN c.cargo ca INNER JOIN c.usuario u INNER JOIN l.setor s INNER JOIN s.unidade un WHERE u.enabled = 1 AND un.id = :unidade ORDER BY s.nome, u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS CRIADAS POR UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS CRIADAS POR UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorOrigem s LEFT JOIN t.criadoPor u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS CRIADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS CRIADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.sigla as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t INNER JOIN t.criadoPor cp LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND cp.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS CRIADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS CRIADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t INNER JOIN t.criadoPor cp LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND cp.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, ur.nome as usuario, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel ur LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND s.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT ur.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel ur WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY ur.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS PARA UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS PARA UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND u.id = :unidade GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO EM UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO EM UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, us.nome, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel us LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo IS NULL AND s.id = :setor ORDER BY us.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO EM UM SETOR ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO EM UM SETOR ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor');
        $tipoRelatorio->setDQL('SELECT u.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s WHERE t.dataHoraConclusaoPrazo IS NULL AND s.id = :setor GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO EM UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO EM UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, us.nome, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.nome as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel us LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :unidade ORDER BY s.sigla ASC, us.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO EM UMA UNIDADE ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO EM UMA UNIDADE ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT s.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :unidade GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.nome as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :usuario ORDER BY t.id ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS EM ABERTO PARA UM USUÁRIO ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS EM ABERTO PARA UM USUÁRIO ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, 
t.dataHoraConclusaoPrazo as conclusao, u.nome as usuario, e.nome as especie FROM SuppCore\AdministrativoBackend\Entity\Tarefa t 
LEFT JOIN t.especieTarefa e 
LEFT JOIN t.usuarioResponsavel u 
LEFT JOIN t.setorResponsavel s 
LEFT JOIN t.processo p 
WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio 
AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND s.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome as setor_resp, uc. nome as usuario_resp, t.dataHoraConclusaoPrazo as data_conc, et.nome as especie, gt.nome as genero 
from SuppCore\AdministrativoBackend\Entity\Tarefa t 
LEFT JOIN t.setorResponsavel s 
LEFT JOIN s.unidade u 
LEFT JOIN t.usuarioConclusaoPrazo uc
LEFT JOIN t.especieTarefa et
LEFT JOIN et.generoTarefa gt
WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND u.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS PENDENTES EM DIAS EM UMA UNIDADE');
        $tipoRelatorio->setDescricao('TAREFAS PENDENTES EM DIAS EM UMA UNIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT p.NUP, pr.nome as procedencia, uo.sigla as unidade_origem, s.sigla as setor_responsavel, us.nome as usuario_responsavel, e.nome as especie, DATE_DIFF(CURRENT_DATE(), t.dataHoraInicioPrazo) as dias_pendente from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel us LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u LEFT JOIN t.setorOrigem so LEFT JOIN so.unidade uo LEFT JOIN t.processo p LEFT JOIN p.procedencia pr WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :unidade ORDER BY s.sigla ASC, us.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES EXTERNAS PENDENTES DE RECEBIMENTO EM UM SETOR (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES EXTERNAS PENDENTES DE RECEBIMENTO EM UM SETOR (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, pe.nome as destino from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.pessoaDestino pe LEFT JOIN t.processo p WHERE t.dataHoraRecebimento IS NULL AND t.interna = 0 AND so.id = :setor ORDER BY t.criadoEm ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES EXTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES EXTERNAS REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, pe.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN so.unidade uno LEFT JOIN t.pessoaDestino pe LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND t.interna = 0 AND uno.id = :unidade ORDER BY t.dataHoraRecebimento DESC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES RECEBIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.setorDestino sd LEFT JOIN sd.unidade usd LEFT JOIN t.processo p WHERE t.dataHoraRecebimento >= :dataHoraInicio AND t.dataHoraRecebimento <= :dataHoraFim AND usd.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES REMETIDAS PARA UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES REMETIDAS PARA UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.setorDestino sd LEFT JOIN sd.unidade usd LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND usd.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES REMETIDAS POR UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN so.unidade uso LEFT JOIN t.setorDestino sd LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND uso.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        /*
         * ---------------------------
         * ---------------------------
         * ---------------------------
         * ---------------------------
         * ---------------------------
         */
        // /new updates above
        // /
        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES PENDENTES EM UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES PENDENTES EM UM SETOR ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, d.nome as destinatario, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN c.pessoaDestino d LEFT JOIN c.processo p WHERE c.dataHoraResposta IS NULL AND c.dataHoraRemessa IS NOT NULL AND so.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-COMUNICAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('COMUNICAÇÕES PENDENTES EM UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('COMUNICAÇÕES PENDENTES EM UMA UNIDADE ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT c.id, p.NUP, d.nome as destinatario, c.dataHoraRemessa as remessa FROM SuppCore\AdministrativoBackend\Entity\DocumentoAvulso c LEFT JOIN c.setorOrigem so LEFT JOIN so.unidade un LEFT JOIN c.pessoaDestino d LEFT JOIN c.processo p WHERE c.dataHoraResposta IS NULL AND c.dataHoraRemessa IS NOT NULL AND un.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS EM UMA UNIDADE HÁ MAIS DE 10 DIAS SEM TAREFAS ABERTAS');
        $tipoRelatorio->setDescricao('NUPS EM UMA UNIDADE HÁ MAIS DE 10 DIAS SEM TAREFAS ABERTAS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, s.nome FROM SuppCore\AdministrativoBackend\Entity\Processo p INNER JOIN p.setorAtual s INNER JOIN s.unidade u WITH u.id = :unidade WHERE p.dataHoraEncerramento IS NULL AND (SELECT MAX(t.dataHoraConclusaoPrazo) FROM SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.processo tp WHERE tp.id = p.id) < DATE_SUB(CURRENT_DATE(), 10, \'day\') ORDER BY s.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS EM UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorAtual s WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND s.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s LEFT JOIN s.unidade u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :unidade');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.setorInicial s LEFT JOIN s.unidade u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :unidade GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.id, p.NUP, p.criadoEm as data_hora from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.criadoPor u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PROCESSOS/DOCUMENTOS AVULSOS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('NUPS GERADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('NUPS GERADOS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(p.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Processo p LEFT JOIN p.criadoPor u WHERE p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim AND u.id = :usuario GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ESPÉCIES DE PROCESSOS/DOCUMENTOS AVULSOS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ESPÉCIES DE PROCESSOS/DOCUMENTOS AVULSOS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT e.id, e.nome, g.nome as genero, e.ativo from SuppCore\AdministrativoBackend\Entity\EspecieProcesso e LEFT JOIN e.generoProcesso g ORDER BY g.nome ASC, e.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ESPÉCIES DE SETORES');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ESPÉCIES DE SETORES');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT e.id, e.nome, g.nome as genero, e.ativo from SuppCore\AdministrativoBackend\Entity\EspecieSetor e LEFT JOIN e.generoSetor g ORDER BY g.nome ASC, e.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('RELAÇÃO DE ESPÉCIES DE TAREFAS');
        $tipoRelatorio->setDescricao('RELAÇÃO DE ESPÉCIES DE TAREFAS');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT e.id, e.nome, g.nome as genero, e.ativo from SuppCore\AdministrativoBackend\Entity\EspecieTarefa e LEFT JOIN e.generoTarefa g ORDER BY g.nome ASC, e.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DE UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS DE UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.sigla as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ABERTAS PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS ABERTAS PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.sigla as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ABERTAS PARA UM USUÁRIO ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS ABERTAS PARA UM USUÁRIO ATUALMENTE (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND s.id = :setor GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND u.id = :unidade GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS PARA UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS PARA UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.sigla as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS DISTRIBUÍDAS PARA UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS DISTRIBUÍDAS PARA UM USUÁRIO EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND u.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS EM UM SETOR EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND s.id = :setor GROUP BY u.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS EM UMA UNIDADE EM UM PERÍODO DE TEMPO (QUANTITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('unidade,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT s.nome, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN s.unidade u WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND u.id = :unidade GROUP BY s.nome');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, t.dataHoraConclusaoPrazo as conclusao, s.sigla as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND u.id = :usuario');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS ENCERRADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUATITATIVO)');
        $tipoRelatorio->setDescricao('TAREFAS ENCERRADAS POR UM USUÁRIO EM UM PERÍODO DE TEMPO (QUATITATIVO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT u.nome, s.sigla, count(t.id) as subtotal from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.setorResponsavel s LEFT JOIN t.usuarioResponsavel u WHERE t.dataHoraConclusaoPrazo >= :dataHoraInicio AND t.dataHoraConclusaoPrazo <= :dataHoraFim AND u.id = :usuario GROUP BY u.nome, s.sigla');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES EXTERNAS REMETIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES EXTERNAS REMETIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, pe.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.pessoaDestino pe LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND t.interna = 0 AND so.id = :setor ORDER BY t.dataHoraRecebimento DESC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES RECEBIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES RECEBIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.setorDestino sd LEFT JOIN t.processo p WHERE t.dataHoraRecebimento >= :dataHoraInicio AND t.dataHoraRecebimento <= :dataHoraFim AND sd.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES REMETIDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES REMETIDAS PARA UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.setorDestino sd LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND sd.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TRAMITAÇÕES REMETIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setDescricao('TRAMITAÇÕES REMETIDAS POR UM SETOR EM UM PERÍODO DE TEMPO (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, so.nome as origem, t.criadoEm as remessa, sd.nome as destino, t.dataHoraRecebimento as recebimento from SuppCore\AdministrativoBackend\Entity\Tramitacao t LEFT JOIN t.setorOrigem so LEFT JOIN t.setorDestino sd LEFT JOIN t.processo p WHERE t.criadoEm >= :dataHoraInicio AND t.criadoEm <= :dataHoraFim AND so.id = :setor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PLANO DE CLASSIFICAÇÃO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM COMPLETA DO PLANO DE CLASSIFICAÇÃO');
        $tipoRelatorio->setDescricao('LISTAGEM COMPLETA DO PLANO DE CLASSIFICAÇÃO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT a.id, a.codigo, a.nome, b.nome AS classificacao_pai, 
                    md.valor AS modalidade_destinacao, a.permissaoUso, a.ativo 
                    FROM SuppCore\AdministrativoBackend\Entity\Classificacao a 
                    LEFT JOIN a.parent b LEFT JOIN a.modalidadeDestinacao md 
                    ORDER BY a.codigo ASC, a.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PLANO DE CLASSIFICAÇÃO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM PARCIAL DO PLANO DE CLASSIFICAÇÃO');
        $tipoRelatorio->setDescricao('LISTAGEM PARCIAL DO PLANO DE CLASSIFICAÇÃO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('classificacao');
        $tipoRelatorio->setDQL('SELECT a.id, a.codigo, a.nome, b.nome AS classificacao, 
                    md.valor AS modalidade_destinacao, a.permissaoUso, a.ativo 
                    FROM SuppCore\AdministrativoBackend\Entity\Classificacao a 
                    LEFT JOIN a.parent b LEFT JOIN a.modalidadeDestinacao md 
                    WHERE a.parent = :classificacao 
                    ORDER BY a.codigo ASC, a.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PLANO DE CLASSIFICAÇÃO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setDescricao('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('classificacao,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.NUP, s.nome AS setorAtual, ep.nome AS especieProcesso
                 from SuppCore\AdministrativoBackend\Entity\Processo p 
                 LEFT JOIN p.setorAtual s 
                 LEFT JOIN p.especieProcesso ep
                 WHERE p.classificacao = :classificacao 
                 AND p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PLANO DE CLASSIFICAÇÃO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR ATUAL EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setDescricao('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR ATUAL EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('classificacao,setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.NUP, s.nome AS setorAtual, u. nome AS unidadeAtual, ep.nome AS especieProcesso
                 from SuppCore\AdministrativoBackend\Entity\Processo p 
                 LEFT JOIN p.setorAtual s 
                 LEFT JOIN p.especieProcesso ep
                 LEFT JOIN s.unidade u
                 WHERE p.classificacao = :classificacao AND p.setorAtual = :setor 
                 AND p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-PLANO DE CLASSIFICAÇÃO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR INICIAL EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setDescricao('LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR INICIAL EM UM PERÍODO DE TEMPO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('classificacao,setor,dataHoraInicio,dataHoraFim');
        $tipoRelatorio->setDQL('SELECT p.NUP, s.nome AS setorInicial, u. nome AS unidadeAtual, ep.titulo AS especieProcesso
                 from SuppCore\AdministrativoBackend\Entity\Processo p 
                 LEFT JOIN p.setorInicial s 
                 LEFT JOIN p.especieProcesso ep
                 LEFT JOIN s.unidade u
                 WHERE p.classificacao = :classificacao AND p.setorInicial = :setor 
                 AND p.criadoEm >= :dataHoraInicio AND p.criadoEm <= :dataHoraFim');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELA DE TEMPORALIDADE', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM COMPLETA DA TABELA DE TEMPORALIDADE');
        $tipoRelatorio->setDescricao('LISTAGEM COMPLETA DA TABELA DE TEMPORALIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros(' ');
        $tipoRelatorio->setDQL('SELECT a.id, a.codigo, a.nome, 
                  a.prazoGuardaFaseCorrenteAno AS prazo_guarda_corrente, 
                  a.prazoGuardaFaseCorrenteEvento AS prazo_guarda_corrente_evento, 
                  a.prazoGuardaFaseIntermediariaAno AS prazo_guarda_intermediaria, 
                  md.valor AS modalidade_destinacao, a.permissaoUso, a.ativo 
                  FROM SuppCore\AdministrativoBackend\Entity\Classificacao a 
                  LEFT JOIN a.modalidadeDestinacao md 
                  ORDER BY a.codigo ASC, a.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELA DE TEMPORALIDADE', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM PARCIAL DA TABELA DE TEMPORALIDADE');
        $tipoRelatorio->setDescricao('LISTAGEM PARCIAL DA TABELA DE TEMPORALIDADE');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('classificacao');
        $tipoRelatorio->setDQL('SELECT a.id, a.codigo, a.nome, 
                  a.prazoGuardaFaseCorrenteAno AS prazo_guarda_corrente, 
                  a.prazoGuardaFaseCorrenteEvento AS prazo_guarda_corrente_evento, 
                  a.prazoGuardaFaseIntermediariaAno AS prazo_guarda_intermediaria, 
                  b.nome AS classificacao, 
                  md.valor AS modalidade_destinacao, a.permissaoUso, a.ativo 
                  FROM SuppCore\AdministrativoBackend\Entity\Classificacao a 
                  LEFT JOIN a.parent b 
                  LEFT JOIN a.modalidadeDestinacao md 
                  WHERE a.parent = :classificacao 
                  ORDER BY a.codigo ASC, a.nome ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TABELA DE TEMPORALIDADE', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DE PROCESSOS POR PRAZO DE GUARDA');
        $tipoRelatorio->setDescricao('LISTAGEM DE PROCESSOS POR PRAZO DE GUARDA');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('prazoGuardaFaseCorrenteAno');
        $tipoRelatorio->setDQL('SELECT p.NUP, s.nome AS setorAtual, u. nome AS unidadeAtual, ep.nome AS especieProcesso
              from SuppCore\AdministrativoBackend\Entity\Processo p
              INNER JOIN p.classificacao c
              LEFT JOIN p.setorAtual s
              LEFT JOIN p.especieProcesso ep
              LEFT JOIN s.unidade u
              WHERE c.prazoGuardaFaseCorrenteAno = :prazoGuardaFaseCorrenteAno');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TRAMITAÇÕES', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DO FLUXO DE TRAMITAÇÕES DE UM PROCESSO');
        $tipoRelatorio->setDescricao('LISTAGEM DO FLUXO DE TRAMITAÇÕES DE UM PROCESSO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('processo');
        $tipoRelatorio->setDQL('SELECT p.NUP, so.nome AS setorOrigem, sd.nome AS setorDestino, 
                 pd.nome AS pessoaDestino
                 from SuppCore\AdministrativoBackend\Entity\Tramitacao t
                 INNER JOIN t.processo p
                 INNER JOIN t.setorOrigem so
                 INNER JOIN t.setorDestino sd
                 INNER JOIN t.pessoaDestino pd
                 WHERE p.id = :processo');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-FLUXO DE TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DO FLUXO DE TAREFAS DE UM PROCESSO');
        $tipoRelatorio->setDescricao('LISTAGEM DO FLUXO DE TAREFAS DE UM PROCESSO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('processo');
        $tipoRelatorio->setDQL('SELECT t.id AS tarefa_id, p.NUP, t.dataHoraInicioPrazo AS inicio_prazo, 
                 t.dataHoraFinalPrazo AS final_prazo, s.sigla AS setor, e.nome AS especie 
                 from SuppCore\AdministrativoBackend\Entity\Tarefa t
                 LEFT JOIN t.especieTarefa e
                 LEFT JOIN t.usuarioResponsavel u
                 LEFT JOIN t.setorResponsavel s
                 LEFT JOIN t.processo p 
                 WHERE p.id = :processo');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-HISTÓRICO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DO HISTÓRICO DE CLASSIFICAÇÕES DE UM PROCESSO');
        $tipoRelatorio->setDescricao('LISTAGEM DO HISTÓRICO DE CLASSIFICAÇÕES DE UM PROCESSO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('processo');
        $tipoRelatorio->setDQL('SELECT log.id AS id, log.loggedAt AS data, 
            log.data AS classificacao , u.nome AS alteradoPor
            FROM Gedmo\Loggable\Entity\LogEntry log
            INNER JOIN SuppCore\AdministrativoBackend\Entity\Usuario u
            WHERE log.objectClass = \'SuppCore\AdministrativoBackend\Entity\Processo\'
            AND log.objectId = :processo
            AND u.username = log.username');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-HISTÓRICO', EspecieRelatorio::class));
        $tipoRelatorio->setNome('LISTAGEM DE TRANSAÇÕES REALIZADAS EM UM PROCESSO');
        $tipoRelatorio->setDescricao('LISTAGEM DE TRANSAÇÕES REALIZADAS EM UM PROCESSO');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('processo');
        $tipoRelatorio->setDQL('SELECT h.id, h.descricao, p.NUP, u.nome AS criadoPor, h.criadoEm 
                 FROM SuppCore\AdministrativoBackend\Entity\Historico h
                 INNER JOIN SuppCore\AdministrativoBackend\Entity\Processo p
                 INNER JOIN SuppCore\AdministrativoBackend\Entity\Usuario u
                 WHERE h.processo = :processo
                 AND p.id = h.processo
                 AND u.id = h.criadoPor');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

        $tipoRelatorio = new TipoRelatorio();
        $tipoRelatorio->setEspecieRelatorio($this->getReference('EspecieRelatorio-TAREFAS', EspecieRelatorio::class));
        $tipoRelatorio->setNome('TAREFAS SELECIONADAS PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setDescricao('TAREFAS SELECIONADAS PARA UM USUÁRIO ATUALMENTE (DETALHADO)');
        $tipoRelatorio->setTemplateHTML('Resources/Relatorio/lista.html.twig');
        $tipoRelatorio->setParametros('usuario,tarefas');
        $tipoRelatorio->setDQL('SELECT t.id, p.NUP, t.dataHoraInicioPrazo as inicio_prazo, t.dataHoraFinalPrazo as final_prazo, s.nome as setor, e.nome as especie from SuppCore\AdministrativoBackend\Entity\Tarefa t LEFT JOIN t.especieTarefa e LEFT JOIN t.usuarioResponsavel u LEFT JOIN t.setorResponsavel s LEFT JOIN t.processo p WHERE t.dataHoraConclusaoPrazo IS NULL AND u.id = :usuario AND t.id IN (:tarefas) ORDER BY t.id ASC');
        $manager->persist($tipoRelatorio);
        $this->addReference('TipoRelatorio-'.$tipoRelatorio->getNome(), $tipoRelatorio);

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
