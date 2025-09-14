<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Repositorio/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function hash;
use function strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Cria o documento e o componente digital do repositório!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private DocumentoResource $documentoResource;

    private TipoDocumentoResource $tipoDocumentoResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        DocumentoResource $documentoResource,
        TipoDocumentoResource $tipoDocumentoResource,
        ComponenteDigitalResource $componenteDigitalResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->documentoResource = $documentoResource;
        $this->tipoDocumentoResource = $tipoDocumentoResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    public function supports(): array
    {
        return [
            Repositorio::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Repositorio|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $tipoDocumentoRepository = $this->tipoDocumentoResource->getRepository();
        $tipoDocumento = $tipoDocumentoRepository
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_4')]);

        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento($tipoDocumento);
        $documento = $this->documentoResource->create($documentoDTO, $transactionId);

        $restDto->setDocumento($documento);

        $componenteDigitalDTO = new ComponenteDigital();
        $componenteDigitalDTO->setEditavel(true);
        $componenteDigitalDTO->setFileName(
            $tipoDocumento->getNome().'.html'
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

    public function getOrder(): int
    {
        return 1;
    }
}
