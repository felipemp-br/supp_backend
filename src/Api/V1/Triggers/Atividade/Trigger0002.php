<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Atividade/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger  =Caso informados documentos no request que não sejam para submeter à aprovação eles serão juntados!
 * @classeSwagger=Trigger0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private JuntadaResource $juntadaResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        JuntadaResource $juntadaResource
    ) {
        $this->juntadaResource = $juntadaResource;
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Atividade|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ('juntar' === $restDto->getDestinacaoMinutas()) {
            /** @var Atividade $atividade */
            $atividade = $restDto;

            $documentosId = [];
            foreach ($atividade->getDocumentos() as $documento) {
                $documentosId[] = $documento->getId();
            }
            foreach ($atividade->getTarefa()->getMinutas() as $documento) {
                if (!$documento->getJuntadaAtual() &&
                    (
                        $atividade->getEncerraTarefa() ||
                        in_array(
                            $documento->getId(),
                            $documentosId
                        )
                    )
                ) {
                    $juntadaDTO = new Juntada();
                    $juntadaDTO->setAtividade($entity);
                    $juntadaDTO->setDocumento($documento);
                    $juntadaDTO->setDescricao($entity->getEspecieAtividade()->getNome());

                    $this->juntadaResource->create($juntadaDTO, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
