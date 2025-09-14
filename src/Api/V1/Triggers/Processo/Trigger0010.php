<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use DateInterval;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0010.
 *
 * @descSwagger=Realiza o cálculo da data provável da próxima transição arquivística!
 * @classeSwagger=Trigger0010
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0010 implements TriggerInterface
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
        if (!$restDto->getClassificacao() ||
            !$restDto->getModalidadeFase() ||
            !$restDto->getDataHoraAbertura()) {
            return;
        }

        // transição ou arquivamento?
        if ($restDto->getModalidadeFase()->getId() !== $entity->getModalidadeFase()->getId() ||
            ($restDto->getSetorAtual()->getId() !== $entity->getSetorAtual()->getId() &&
                'ARQUIVO' === $restDto->getSetorAtual()->getEspecieSetor()->getNome())) {
            $restDto->setDataHoraProximaTransicao(null);
            switch ($restDto->getModalidadeFase()->getValor()) {
                case 'CORRENTE':
                    // calculo da temporalidade para quando não for orientado por eventos
                    if (!$restDto->getClassificacao()->getPrazoGuardaFaseCorrenteEvento()) {
                        $transicaoDesarquivamento = $this->transicaoRepository->findUltimaCriadaByProcessoId(
                            $restDto->getId()
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
                        if ($dataHoraTransicao) {
                            $restDto->setDataHoraProximaTransicao($dataHoraTransicao);
                        }
                    }
                    break;

                case 'INTERMEDIÁRIA':
                    // calculo da temporalidade para quando não for orientado por eventos
                    if (!$restDto->getClassificacao()->getPrazoGuardaFaseIntermediariaEvento()) {
                        $dataHoraTransicao = new DateTime();

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
