<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Caso seja informado um documento origem, a vinculacao entre os documentos origem e do componente digital será criada!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private VinculacaoDocumentoResource $vinculacaoDocumentoResource;

    private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->vinculacaoDocumentoResource = $vinculacaoDocumentoResource;
        $this->modalidadeVinculacaoDocumentoRepository = $modalidadeVinculacaoDocumentoRepository;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'afterCreate',
                'afterAprovar',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getDocumentoOrigem()) {
            $modalidadeVinculacaoDocumento = $this->modalidadeVinculacaoDocumentoRepository->findOneBy(
                ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_documento.const_1')]
            );

            $vinculacaoDocumentoDTO = new VinculacaoDocumento();
            $vinculacaoDocumentoDTO->setDocumento($restDto->getDocumentoOrigem());
            $vinculacaoDocumentoDTO->setDocumentoVinculado($entity->getDocumento());
            $vinculacaoDocumentoDTO->setModalidadeVinculacaoDocumento($modalidadeVinculacaoDocumento);

            $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 4;
    }
}
