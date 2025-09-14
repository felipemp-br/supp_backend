<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Caso seja o documento juntado esteja vinculado a uma tarefa será retirada a etiqueta MINUTA!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    private EtiquetaRepository $etiquetaRepository;

    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0007 constructor.
     */
    public function __construct(
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        EtiquetaRepository $etiquetaRepository,
        TokenStorageInterface $tokenStorage,
        private TarefaResource $tarefaResource

    ) {
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
        $this->etiquetaRepository = $etiquetaRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param EntityInterface|JuntadaEntity $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        try {
            $tarefaOrigem = $entity->getDocumento()->getTarefaOrigem();
            if ($tarefaOrigem &&
                !$tarefaOrigem->getDataHoraConclusaoPrazo()) {
                foreach ($tarefaOrigem->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                    if ($entity->getDocumento()->getUuid() === $vinculacaoEtiqueta->getObjectUuid() &&
                        DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                        $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiqueta->getId(), $transactionId);
                        break;
                    }
                }
            }
        } catch (EntityNotFoundException) {
            // softdeleatble
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
