<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Sigilo/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Sigilo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo as SigiloDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Sigilo as SigiloEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Repository\SigiloRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Apaga eventual etiqueta de sigiloso no processo ou no documento!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    private SigiloRepository $sigiloRepository;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        SigiloRepository $sigiloRepository
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
        $this->sigiloRepository = $sigiloRepository;
    }

    public function supports(): array
    {
        return [
            SigiloEntity::class => [
                'afterDelete',
            ],
        ];
    }

    /**
     * @param SigiloDTO|RestDtoInterface|null $restDto
     * @param SigiloEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $target = false;
        $repositoryMethod = '';
        if ($entity->getProcesso()) {
            $target = $entity->getProcesso();
            $repositoryMethod = 'findCountSigilosAtivosByProcessoId';
        }
        if ($entity->getDocumento()) {
            $target = $entity->getDocumento();
            $repositoryMethod = 'findCountSigilosAtivosByDocumentoId';
        }
        if ($target && $target->getId() &&
            1 === $this->sigiloRepository->$repositoryMethod($target->getId())) {
            foreach ($target->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if (SigiloDTO::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiqueta->getId(), $transactionId);
                    break;
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
