<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo;

use Exception;
use function hash;
use function strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Cria o documento e o componente digital do modelo!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * Trigger0002 constructor.
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
            Modelo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Modelo|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $tipoDocumento = $restDto->getTemplate()->getDocumento()->getTipoDocumento();

        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento($tipoDocumento);

        if($restDto->getDocumentoOrigem()) {
            $documento = $this->documentoResource->clonar($restDto->getDocumentoOrigem()->getId(), null, $transactionId);
        } else {
            $documento = $this->documentoResource->create($documentoDTO, $transactionId);

            $componenteDigitalDTO = new ComponenteDigital();
            $componenteDigitalDTO->setEditavel(true);
            $componenteDigitalDTO->setFileName(
                $restDto->getTemplate()->getDocumento()->getTipoDocumento()->getNome().'.html'
            );
            $componenteDigitalDTO->setMimetype('text/html');
            $componenteDigitalDTO->setNivelComposicao(3);
            $componenteDigitalDTO->setExtensao('html');
            $componenteDigitalDTO->setConteudo('<p>Em branco...</p>');
            $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
            $componenteDigitalDTO->setTamanho(strlen($componenteDigitalDTO->getConteudo()));
            $componenteDigitalDTO->setDocumento($documento);
    
            $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
            
        }

        $restDto->setDocumento($documento);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
