<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use DateInterval;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0009.
 *
 * @descSwagger=Realiza o cálculo da data provável da próxima transição arquivística!
 * @classeSwagger=Trigger0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0009 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Processo::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getClassificacao() ||
            !$restDto->getModalidadeFase() ||
            !$restDto->getDataHoraAbertura() ||
            $restDto->getClassificacao()->getPrazoGuardaFaseCorrenteEvento()) {
            return;
        }

        // calculo da temporalidade para quando não for orientado por eventos
        $dataHoraTransicao = clone $restDto->getDataHoraAbertura();

        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteAno()) {
            $dataHoraTransicao->add(
                new DateInterval('P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteAno().'Y')
            );
        }

        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteMes()) {
            $dataHoraTransicao->add(
                new DateInterval('P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteMes().'M')
            );
        }

        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteDia()) {
            $dataHoraTransicao->add(
                new DateInterval('P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteDia().'D')
            );
        }

        $restDto->setDataHoraProximaTransicao($dataHoraTransicao);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
