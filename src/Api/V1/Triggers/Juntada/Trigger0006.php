<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Juntada/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

use function in_array;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Promove a juntada dos documentos vinculados!
 *
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    /**
     * Trigger0006 constructor.
     */
    public function __construct(
        private readonly JuntadaResource $juntadaResource,
        private readonly TransactionManager $transactionManager,
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param JuntadaDTO|RestDtoInterface|null $restDto
     * @param EntityInterface                  $entity
     * @param string                           $transactionId
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        JuntadaDTO|RestDtoInterface|null $restDto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        // rev. 26/09/2024 - issue1700 - Fatal Error: Nesting level too deep - recursive dependency
        $documentosVinculados = [
            ...$restDto->getDocumento()?->getVinculacoesDocumentos()->toArray(),
            ...array_map(
                static fn (VinculacaoDocumentoEntity $v) => $v,
                array_filter(
                    $this->transactionManager->getScheduledEntities(VinculacaoDocumentoEntity::class, $transactionId),
                    static fn (VinculacaoDocumentoEntity $v) => $v->getDocumento()?->getUuid() === $restDto->getDocumento()?->getUuid()
                        && !in_array($v, $restDto->getDocumento()?->getVinculacoesDocumentos()->toArray(), true)
                )
            ),
        ];

        /** @var VinculacaoDocumentoEntity $vinculacaoDocumento */
        foreach ($documentosVinculados as $vinculacaoDocumento) {
            $juntadaDTO = new JuntadaDTO();
            $juntadaDTO->setDocumento($vinculacaoDocumento->getDocumentoVinculado());
            $juntadaDTO->setVolume($restDto->getVolume());

            $juntadaDTO->setVinculada(true);

            $juntadaDTO->setDescricao($restDto->getDescricao());

            $juntadaDTO->setAtividade($restDto->getAtividade());
            $juntadaDTO->setTarefa($restDto->getTarefa());
            $juntadaDTO->setDocumentoAvulso($restDto->getDocumentoAvulso());
            $juntadaDTO->setJuntadaDesentranhada($restDto->getJuntadaDesentranhada());

            $this->juntadaResource->create($juntadaDTO, $transactionId);
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}
