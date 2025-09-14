<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadComponenteDigitalData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

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
        $componenteDigital->setFileName('DESPACHO.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><u><strong>DESPACHO n. <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></u></p><p> </p><p class="esquerda"><strong>NUP: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>INTERESSADOS: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p class="esquerda"><strong>ASSUNTOS: <span data-method="assuntos" data-options="" data-service="supp_administrativo.template_renderer">*assuntos*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setEditavel(true);
        $componenteDigital->setExtensao('html');
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE DESPACHO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE DESPACHO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('DESPACHO EM BRANCO.html');
        $componenteDigital->setConteudo('<p class="numerado">Em branco...</p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO DESPACHO EM BRANCO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO DESPACHO EM BRANCO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('OFÍCIO.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="esquerda"><u><strong>OFÍCIO n. <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></u></p><p> </p><p class="direita"><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p class="esquerda">Ao Senhor(a) <span data-method="destinatario" data-options="" data-service="supp_administrativo.template_renderer">*destinatario*</span></p><p class="esquerda"><span data-method="endereco" data-options="" data-service="supp_administrativo.template_renderer">*endereco*</span></p><p class="esquerda"> </p><p class="esquerda"><strong>NUP: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>INTERESSADOS: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p class="esquerda"><strong>ASSUNTOS: <span data-method="assuntos" data-options="" data-service="supp_administrativo.template_renderer">*assuntos*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p>Atenciosamente,</p><p> </p><p><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-TEMPLATE OFÍCIO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-TEMPLATE OFÍCIO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('OFÍCIO EM BRANCO.html');
        $componenteDigital->setConteudo('<p class="numerado">Em branco...</p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO OFÍCIO EM BRANCO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO OFÍCIO EM BRANCO',
            $componenteDigital
        );

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setFileName('APROVAÇÃO DE DOCUMENTO.html');
        $componenteDigital->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><u><strong>DESPACHO n. <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></u></p><p> </p><p class="esquerda"><strong>NUP: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>INTERESSADOS: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p class="esquerda"><strong>ASSUNTOS: <span data-method="assuntos" data-options="" data-service="supp_administrativo.template_renderer">*assuntos*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigital->setTamanho(strlen($componenteDigital->getConteudo()));
        $componenteDigital->setHash(hash('SHA256', $componenteDigital->getConteudo()));
        $componenteDigital->setExtensao('html');
        $componenteDigital->setEditavel(true);
        $componenteDigital->setMimetype('text/html');
        $componenteDigital->setDocumento($this->getReference('Documento-MODELO DESPACHO DE APROVAÇÃO', Documento::class));

        $manager->persist($componenteDigital);

        $this->addReference(
            'ComponenteDigital-MODELO DESPACHO DE APROVAÇÃO',
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
        return ['prod', 'dev', 'test'];
    }
}
