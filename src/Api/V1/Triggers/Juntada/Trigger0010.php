<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function count;

/**
 * Class Trigger0010.
 *
 * @descSwagger=Cria novo volume automaticamente caso ultrapasse tamanho máximo configurado!
 *
 * @classeSwagger=Trigger0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0010 implements TriggerInterface
{
    /**
     * Trigger0010 constructor.
     *
     * @param VolumeResource        $volumeResource
     * @param TransactionManager    $transactionManager
     * @param JuntadaResource       $juntadaResource
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private VolumeResource $volumeResource,
        private TransactionManager $transactionManager,
        private JuntadaResource $juntadaResource,
        private ParameterBagInterface $parameterBag
    ) {
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
     * @param Juntada|RestDtoInterface|null $restDto
     * @param JuntadaEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // Verifico a quantidade de arquivos juntados no volume
        $limiteVolume = $this->parameterBag
            ->get('supp_core.administrativo_backend.volume_quantidade_documento');

        if ($limiteVolume) {
            if ($entity->getVolume() && $entity->getVolume()->getId()) {
                $volume = $entity->getVolume();
                $processoOrigem = $entity->getVolume()->getProcesso();

                $totalJuntadaPorTransacao = count($this->transactionManager->getScheduledEntities(
                    JuntadaEntity::class,
                    $transactionId
                ));
                $juntadasVolume = $this->juntadaResource->getRepository()->totalJuntadaByVolumeId($volume->getId());
                if ('ELETRÔNICO' === $processoOrigem->getModalidadeMeio()->getValor()
                    && ($juntadasVolume + $totalJuntadaPorTransacao) >= $limiteVolume) {
                    // Crio um novo volume
                    $volumeDTO = new Volume();
                    $volumeDTO->setNumeracaoSequencial($volume->getNumeracaoSequencial() + 1);
                    $volumeDTO->setProcesso($restDto->getDocumento()->getTarefaOrigem()->getProcesso());
                    $volumeDTO->setModalidadeMeio($restDto->getDocumento()->getTarefaOrigem()->getProcesso()
                        ->getModalidadeMeio());
                    $this->volumeResource->create($volumeDTO, $transactionId);

                    // seta o volume antigo como encerrado
                    /** @var Volume $volumeDTOAntigo */
                    $volumeDTOAntigo = $this->volumeResource->getDtoForEntity(
                        $volume->getId(),
                        Volume::class
                    );
                    $volumeDTOAntigo->setEncerrado(true);
                    $this->volumeResource->update($volume->getId(), $volumeDTOAntigo, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
