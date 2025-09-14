<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;

/**
 * ProcessaStatusErroSolicitacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusErroSolicitacao
{
    /**
     * Constructor.
     *
     * @param SetorResource $setorResource
     */
    public function __construct(
        protected readonly SetorResource $setorResource,
    ) {
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver
     * @param string                        $transactionId
     *
     * @return void
     */
    public function handle(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId
    ): void {
        $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();
        $tarefaDTO = $salarioMaternidadeRuralDriver->buildTarefaErroSolicitacao(
            $solicitacaoEntity,
            $solicitacaoDTO
        );
        if ($tarefaDTO->getSetorResponsavel()->getId() !== $configSalarioMaternidadeRural['setor_dados_cumprimento']) {
          $setorEntity = $this->setorResource->findOne(
              (int)$configSalarioMaternidadeRural['setor_dados_cumprimento']
          );
          $tarefaDTO->setSetorResponsavel($setorEntity);
        }
        $salarioMaternidadeRuralDriver->createTarefa(
            $tarefaDTO,
            $transactionId
        );
    }
}
