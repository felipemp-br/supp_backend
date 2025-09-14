<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Classificacao/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Classificacao;

use DateInterval;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Realiza o cálculo da data provável da próxima transição arquivística!
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private ProcessoResource $processoResource;
    private TransicaoRepository $transicaoRepository;

    /**
     * Trigger0010 constructor.
     */
    public function __construct(
        ProcessoResource $processoResource,
        TransicaoRepository $transicaoRepository
    ) {
        $this->processoResource = $processoResource;
        $this->transicaoRepository = $transicaoRepository;
    }

    public function supports(): array
    {
        return [
            Classificacao::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Classificacao|RestDtoInterface|null $restDto
     * @param ClassificacaoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (($restDto->getPrazoGuardaFaseCorrenteDia() !== $entity->getPrazoGuardaFaseCorrenteDia()) ||
            ($restDto->getPrazoGuardaFaseCorrenteMes() !== $entity->getPrazoGuardaFaseCorrenteMes()) ||
            ($restDto->getPrazoGuardaFaseCorrenteAno() !== $entity->getPrazoGuardaFaseCorrenteAno()) ||
            ($restDto->getPrazoGuardaFaseCorrenteEvento() !== $entity->getPrazoGuardaFaseCorrenteEvento())) {
            //recomputa os processos na fase corrente
            $processosEntities = $this->processoResource->getRepository()
                ->findByModalidadeFaseValorAndClassificacaoId(
                    'CORRENTE',
                    $entity->getId()
                );

            foreach ($processosEntities as $processo) {
                // calculo da temporalidade para quando não for orientado por eventos
                $dataHoraTransicao = null;
                if (!$restDto->getPrazoGuardaFaseCorrenteEvento()) {
                    $transicaoDesarquivamento = $this->transicaoRepository->findUltimaCriadaByProcessoId(
                        $processo->getId()
                    );

                    if ($transicaoDesarquivamento) {
                        $dataHoraTransicao = $transicaoDesarquivamento->getCriadoEm();
                    } else {
                        $dataHoraTransicao = clone $processo->getDataHoraAbertura();
                    }

                    if ($restDto->getPrazoGuardaFaseCorrenteAno()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseCorrenteAno().'Y'
                            )
                        );
                    }

                    if ($restDto->getPrazoGuardaFaseCorrenteMes()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseCorrenteMes().'M'
                            )
                        );
                    }

                    if ($restDto->getPrazoGuardaFaseCorrenteDia()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseCorrenteDia().'D'
                            )
                        );
                    }
                }

                /** @var Processo $processoDto */
                $processoDto = $this->processoResource->getDtoForEntity(
                    $processo->getId(),
                    Processo::class
                );

                $processoDto->setDataHoraProximaTransicao($dataHoraTransicao);
                $this->processoResource->update($processo->getId(), $processoDto, $transactionId);
            }
        }

        if (($restDto->getPrazoGuardaFaseIntermediariaDia() !== $entity->getPrazoGuardaFaseIntermediariaDia()) ||
            ($restDto->getPrazoGuardaFaseIntermediariaMes() !== $entity->getPrazoGuardaFaseIntermediariaMes()) ||
            ($restDto->getPrazoGuardaFaseIntermediariaAno() !== $entity->getPrazoGuardaFaseIntermediariaAno()) ||
            ($restDto->getPrazoGuardaFaseIntermediariaEvento() !== $entity->getPrazoGuardaFaseIntermediariaEvento())) {
            //recomputa os processos na fase intermediaria
            $processosEntities = $this->processoResource->getRepository()
                ->findByModalidadeFaseValorAndClassificacaoId(
                    'INTERMEDIÁRIA',
                    $entity->getId()
                );

            foreach ($processosEntities as $processo) {
                // calculo da temporalidade para quando não for orientado por eventos
                $dataHoraTransicao = null;
                if (!$restDto->getPrazoGuardaFaseIntermediariaEvento()) {
                    $transicaoIntermediaria = $this->transicaoRepository->findUltimaCriadaByProcessoId(
                        $restDto->getId()
                    );

                    if (!$transicaoIntermediaria) {
                        return;
                    }

                    $dataHoraTransicao = $transicaoIntermediaria->getCriadoEm();

                    if ($restDto->getPrazoGuardaFaseIntermediariaAno()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseIntermediariaAno().'Y'
                            )
                        );
                    }

                    if ($restDto->getPrazoGuardaFaseIntermediariaMes()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseIntermediariaMes().'M'
                            )
                        );
                    }

                    if ($restDto->getPrazoGuardaFaseIntermediariaDia()) {
                        $dataHoraTransicao->add(
                            new DateInterval(
                                'P'.$restDto->getPrazoGuardaFaseIntermediariaDia().'D'
                            )
                        );
                    }

                    /** @var Processo $processoDto */
                    $processoDto = $this->processoResource->getDtoForEntity(
                        $processo->getId(),
                        Processo::class
                    );

                    $processoDto->setDataHoraProximaTransicao($dataHoraTransicao);
                    $this->processoResource->update($processo->getId(), $processoDto, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
