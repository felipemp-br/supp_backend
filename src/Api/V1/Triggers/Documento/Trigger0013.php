<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0013.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0013.
 *
 * @descSwagger=Cria a etiqueta da minuta na tarefa
 * @classeSwagger=Trigger0013
 */
class Trigger0013 implements TriggerInterface
{
    public function __construct(
        private EtiquetaRepository $etiquetaRepository,
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterConverteAnexoEmMinuta'
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
        $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
        $vinculacaoEtiquetaDTO->setEtiqueta(
            $this->etiquetaRepository->findOneBy(
                [
                    'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_1'),
                    'sistema' => true,
                ]
            )
        );
        $vinculacaoEtiquetaDTO->setTarefa($restDto->getTarefaOrigem());
        $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
        $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
        $vinculacaoEtiquetaDTO->setLabel($entity->getTipoDocumento()->getSigla());
        $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
