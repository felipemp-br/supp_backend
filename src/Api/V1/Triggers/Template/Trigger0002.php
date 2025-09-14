<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Template/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Template;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Template as TemplateDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeModeloResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModeloResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Template;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Cria um modelo em branco!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ModeloResource $modeloResource;

    private ModalidadeModeloResource $modalidadeModeloResource;

    private DocumentoResource $documentoResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ModeloResource $modeloResource,
        ModalidadeModeloResource $modalidadeModeloResource,
        DocumentoResource $documentoResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->modeloResource = $modeloResource;
        $this->modalidadeModeloResource = $modalidadeModeloResource;
        $this->documentoResource = $documentoResource;
    }

    public function supports(): array
    {
        return [
            TemplateDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var Template $entity */
        $modalidadeModelo = $this->modalidadeModeloResource->getRepository()
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_4')]);
        $tipoDocumento = $restDto->getTipoDocumento();

        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento($tipoDocumento);
        $documento = $this->documentoResource->create($documentoDTO, $transactionId);

        $modeloDTO = new Modelo();
        $modeloDTO->setNome($restDto->getNome().' EM BRANCO');
        $modeloDTO->setDescricao($modeloDTO->getNome());
        $modeloDTO->setModalidadeModelo($modalidadeModelo);
        $modeloDTO->setTemplate($entity);
        $modeloDTO->setDocumento($documento);
        $modelo = $this->modeloResource->create($modeloDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
