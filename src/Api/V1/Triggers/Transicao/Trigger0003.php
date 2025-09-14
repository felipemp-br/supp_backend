<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Transicao/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Transicao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Transicao as TransicaoEntity;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger  =Cria a transicao dos processos apensados e anexados
 * @classeSwagger=Trigger0003
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private TransicaoResource $transicaoResource;

    private TransactionManager $transactionManager;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        TransicaoResource $transicaoResource,
        TransactionManager $transactionManager
    ) {
        $this->transicaoResource = $transicaoResource;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null $restDto
     * @param TransicaoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        foreach ($restDto->getProcesso()->getVinculacoesProcessos() as $vinculacaoProcessoEntity) {
            if ('APENSAMENTO' === $vinculacaoProcessoEntity->getModalidadeVinculacaoProcesso()->getValor() ||
                'ANEXAÇÃO' === $vinculacaoProcessoEntity->getModalidadeVinculacaoProcesso()->getValor()) {
                $this->transactionManager->addContext(
                    new Context('criacaoTransicaoProcessoApensado', true),
                    $transactionId
                );
                /** @var Transicao $transicaoDto */
                $transicaoDto = clone $restDto;
                $transicaoDto->setProcesso($vinculacaoProcessoEntity->getProcessoVinculado());
                $this->transicaoResource->create(
                    $transicaoDto,
                    $transactionId
                );
                $this->transactionManager->removeContext(
                    'criacaoTransicaoProcessoApensado',
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
