<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadComponenteDigitalData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;

use SuppCore\AdministrativoBackend\Entity\Documento;
use function hash;
use function strlen;

/**
 * Class LoadComponenteDigitalData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadComponenteDigitalData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('despacho.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><strong>DESPACHO</strong></p><p> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Interessados: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p> </p><div id="conteudoModelo">digite o texto</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setEditavel(true);
        $componenteDigital->setExtensao('html');
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_DESPACHO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_DESPACHO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('despacho_numerado.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><strong>DESPACHO Nº</strong></p><p> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Interessados: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setEditavel(true);
        $componenteDigital->setExtensao('html');
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_DESPACHO_NUMERADO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_DESPACHO_NUMERADO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('oficio.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="esquerda"><strong>OFÍCIO Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p><p> </p><p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="esquerda">Ao Senhor(a) <span data-method="destinatario" data-options="" data-service="supp_administrativo.template_renderer">*destinatario*</span></p><p class="esquerda"><span data-method="endereco" data-options="" data-service="supp_administrativo.template_renderer">*endereco*</span></p><p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p>Atenciosamente,</p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_OFICIO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_OFICIO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('memorando.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="esquerda"><strong>MEMORANDO Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p><p> </p><p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="esquerda">Ao Senhor(a) <span data-method="destinatario" data-options="" data-service="supp_administrativo.template_renderer">*destinatario*</span></p><p class="esquerda"><span data-method="endereco" data-options="" data-service="supp_administrativo.template_renderer">*endereco*</span></p><p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p>Atenciosamente,</p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_MEMORANDO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_MEMORANDO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('exposicao_motivos.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>EM Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p>
        <p class="esquerda">Excelentíssimo Senhor Presidente da República,</p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Respeitosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_EXPOSICAO_MOTIVOS', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_EXPOSICAO_MOTIVOS',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('nota_tecnica.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>NOTA TÉCNICA Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p>
        <p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Atenciosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_NOTA_TECNICA', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_NOTA_TECNICA',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('mensagem.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>MENSAGEM Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p>
        <p class="esquerda">Excelentíssimo Senhor Presidente do Senado Federal,</p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Respeitosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_MENSAGEM', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_MENSAGEM',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('portaria.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="centralizado"><strong>PORTARIA Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE_PORTARIA', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE_PORTARIA',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('despacho.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><strong>DESPACHO</strong></p><p> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Interessados: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p> </p><div id="conteudoModelo">digite o texto</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setEditavel(true);
        $componenteDigital->setExtensao('html');
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_DESPACHO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_DESPACHO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('despacho_numerado.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><strong>DESPACHO Nº</strong></p><p> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Interessados: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setEditavel(true);
        $componenteDigital->setExtensao('html');
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_DESPACHO_NUMERADO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_DESPACHO_NUMERADO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('oficio.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="esquerda"><strong>OFÍCIO Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p><p> </p><p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="esquerda">Ao Senhor(a) <span data-method="destinatario" data-options="" data-service="supp_administrativo.template_renderer">*destinatario*</span></p><p class="esquerda"><span data-method="endereco" data-options="" data-service="supp_administrativo.template_renderer">*endereco*</span></p><p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p>Atenciosamente,</p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_OFICIO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_OFICIO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('memorando.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="esquerda"><strong>MEMORANDO Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p><p> </p><p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="esquerda">Ao Senhor(a) <span data-method="destinatario" data-options="" data-service="supp_administrativo.template_renderer">*destinatario*</span></p><p class="esquerda"><span data-method="endereco" data-options="" data-service="supp_administrativo.template_renderer">*endereco*</span></p><p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p>Atenciosamente,</p><p> </p><p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_MEMORANDO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_MEMORANDO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('exposicao_motivos.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>EM Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p>
        <p class="esquerda">Excelentíssimo Senhor Presidente da República,</p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Respeitosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_EXPOSICAO_MOTIVOS', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_EXPOSICAO_MOTIVOS',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('nota_tecnica.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>NOTA TÉCNICA Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="esquerda"> </p><p class="esquerda"><strong>Processo: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p>
        <p class="esquerda"><strong>Assunto: [Digite aqui o texto do assunto em negrito]</strong></p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Atenciosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_NOTA_TECNICA', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_NOTA_TECNICA',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('mensagem.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="esquerda"><strong>MENSAGEM Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p>
        <p class="esquerda">Excelentíssimo Senhor Presidente do Senado Federal,</p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p><p>Respeitosamente,</p>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_MENSAGEM', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_MENSAGEM',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('portaria.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p>
        <p class="centralizado"><strong>PORTARIA Nº <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></p>
        <p> </p>
        <div id="conteudoModelo">*conteudoModelo*</div>
        <p> </p>
        <p class="centralizado"><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p>
        <p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_PORTARIA', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_PORTARIA',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('oficio_em_branco.html');
        $componenteDigital->setConteudo('<p> </p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_DESPACHO_EM_BRANCO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_DESPACHO_EM_BRANCO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('oficio_em_branco.html');
        $componenteDigital->setConteudo('<p> </p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO_OFICIO_EM_BRANCO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO_OFICIO_EM_BRANCO',
            $componenteDigital
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
        return ['prodexec'];
    }
}
