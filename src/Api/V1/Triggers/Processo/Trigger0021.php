<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0021.php.
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
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0021.
 *
 * @descSwagger  =Realiza o cálculo da data provável da próxima transição arquivística na alteração de classificação!
 * @classeSwagger=Trigger0021
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0021 implements TriggerInterface
{
    private TransicaoRepository $transicaoRepository;

    /**
     * Trigger0010 constructor.
     */
    public function __construct(
        TransicaoRepository $transicaoRepository
    ) {
        $this->transicaoRepository = $transicaoRepository;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch',
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
        if ($restDto->getClassificacao() &&
            ($restDto->getClassificacao()->getId() !== $entity->getClassificacao()->getId())) {
            $restDto->setDataHoraProximaTransicao(null);
            switch ($entity->getModalidadeFase()->getValor()) {
                case 'CORRENTE':
                    // calculo da temporalidade para quando não for orientado por eventos
                    if (!$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteEvento()) {
                        $transicaoDesarquivamento = $this->transicaoRepository->findUltimaCriadaByProcessoId(
                            $entity->getId()
                        );

                        $dataHoraTransicao = null;
                        if ($transicaoDesarquivamento) {
                            $dataHoraTransicao = $transicaoDesarquivamento->getCriadoEm();
                        } elseif ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteAno() ||
                            $restDto->getClassificacao()->getPrazoGuardaFaseCorrenteMes() ||
                            $restDto->getClassificacao()->getPrazoGuardaFaseCorrenteDia()) {
                            $dataHoraTransicao = clone $restDto->getDataHoraAbertura();
                        }

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteAno()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteAno().'Y'
                                )
                            );
                        }

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteMes()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteMes().'M'
                                )
                            );
                        }

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseCorrenteDia()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteDia().'D'
                                )
                            );
                        }

                        $restDto->setDataHoraProximaTransicao($dataHoraTransicao);
                    }
                    break;

                case 'INTERMEDIÁRIA':
                    // calculo da temporalidade para quando não for orientado por eventos
                    if (!$restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaEvento()) {
                        $transicaoIntermediaria = $this->transicaoRepository->findUltimaCriadaByProcessoId(
                            $restDto->getId()
                        );

                        if (!$transicaoIntermediaria) {
                            return;
                        }

                        $dataHoraTransicao = $transicaoIntermediaria->getCriadoEm();

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaAno()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaAno().'Y'
                                )
                            );
                        }

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaMes()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaMes().'M'
                                )
                            );
                        }

                        if ($restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaDia()) {
                            $dataHoraTransicao->add(
                                new DateInterval(
                                    'P'.$restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaDia().'D'
                                )
                            );
                        }

                        $restDto->setDataHoraProximaTransicao($dataHoraTransicao);
                    }
                    break;
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
