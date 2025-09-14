<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0012.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Remove a vinculação do anexo com a minuta
 * @classeSwagger=Trigger0012
 */
class Trigger0012 implements TriggerInterface
{
    public function __construct(
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeConverteAnexoEmMinuta'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // Removendo vinculacao do documento com a minuta
        $vinculacaoDocumento = $this->vinculacaoDocumentoResource->findOneBy([
            'documentoVinculado' => $restDto->getId(),
            'modalidadeVinculacaoDocumento' => $this->modalidadeVinculacaoDocumentoRepository->findOneBy(
                ['valor' => $this->parameterBag->get(
                    'constantes.entidades.modalidade_vinculacao_documento.const_1'
                )]
            )
        ]);

        if ($vinculacaoDocumento) {
            $this->vinculacaoDocumentoResource->delete($vinculacaoDocumento->getId(), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
