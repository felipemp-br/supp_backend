<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Template/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Template;

use Exception;
use function hash;
use function strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Template;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria o documento e o componente digital do modelo!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        DocumentoResource $documentoResource,
        ComponenteDigitalResource $componenteDigitalResource
    ) {
        $this->documentoResource = $documentoResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    public function supports(): array
    {
        return [
            Template::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Template|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $tipoDocumento = $restDto->getTipoDocumento();

        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento($tipoDocumento);
        $documento = $this->documentoResource->create($documentoDTO, $transactionId);

        $restDto->setDocumento($documento);

        $componenteDigitalDTO = new ComponenteDigital();
        $componenteDigitalDTO->setEditavel(true);
        $componenteDigitalDTO->setFileName(
            $restDto->getDocumento()->getTipoDocumento()->getNome().'.html'
        );
        $componenteDigitalDTO->setMimetype('text/html');
        $componenteDigitalDTO->setNivelComposicao(3);
        $componenteDigitalDTO->setExtensao('html');
        $componenteDigitalDTO->setConteudo('<p class="centralizado"><span data-method="cabecalho" data-options="" data-service="supp_main.template_renderer">*cabecalho*</span></p><p class="centralizado"><u><strong>TEMPLATE n. <span data-method="numeroDocumento" data-options="" data-service="supp_administrativo.template_renderer">*numeroDocumento*</span></strong></u></p><p> </p><p class="esquerda"><strong>NUP: <span data-method="nup" data-options="" data-service="supp_administrativo.template_renderer">*nup*</span></strong></p><p class="esquerda"><strong>INTERESSADOS: <span data-method="interessados" data-options="" data-service="supp_administrativo.template_renderer">*interessados*</span></strong></p><p class="esquerda"><strong>ASSUNTOS: <span data-method="assuntos" data-options="" data-service="supp_administrativo.template_renderer">*assuntos*</span></strong></p><p> </p><div id="conteudoModelo">*conteudoModelo*</div><p> </p><p><span data-method="localData" data-options="" data-service="supp_main.template_renderer">*localData*</span></p><p> </p><p><span data-method="assinaturaUsuario" data-options="" data-service="supp_main.template_renderer">*assinaturaUsuario*</span></p><p><span data-method="chaveAcesso" data-options="" data-service="supp_administrativo.template_renderer">*chaveAcesso*</span></p>');
        $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setTamanho(strlen($componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setDocumento($documento);

        $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
