<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Coloca uma etiqueta da tarefa redistribuída!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    /**
     * Trigger0007 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param EntityInterface|TarefaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($entity->getUsuarioResponsavel()->getId() !== $restDto->getUsuarioResponsavel()->getId()) {
            $vinculacaoEtiquetaExistente = false;
            foreach ($entity->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                if ($vinculacaoEtiqueta->getEtiqueta()->getSistema() &&
                    'REDISTRIBUÍDA' === $vinculacaoEtiqueta->getEtiqueta()->getNome() &&
                    $entity->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                    TarefaEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                    $vinculacaoEtiquetaExistente = $vinculacaoEtiqueta;
                    break;
                }
            }

            if (!$vinculacaoEtiquetaExistente) {
                $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
                $vinculacaoEtiquetaDTO->setEtiqueta(
                    $this->etiquetaRepository->findOneBy(
                        [
                            'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_5'),
                            'sistema' => true,
                        ]
                    )
                );
                $vinculacaoEtiquetaDTO->setTarefa($entity);
                $vinculacaoEtiquetaDTO->setObjectClass(get_class($entity));
                $vinculacaoEtiquetaDTO->setObjectUuid($entity->getUuid());
                $vinculacaoEtiquetaDTO->setLabel($entity->getUsuarioResponsavel()->getNome());
                $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
            } else {
                $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                    $vinculacaoEtiquetaExistente->getId(),
                    VinculacaoEtiqueta::class
                );
                $vinculacaoEtiquetaDTO->setLabel($entity->getUsuarioResponsavel()->getNome());
                $this->vinculacaoEtiquetaResource->update(
                    $vinculacaoEtiquetaExistente->getId(),
                    $vinculacaoEtiquetaDTO,
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
