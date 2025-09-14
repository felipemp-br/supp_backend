<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;

/**
 * ProcessaStatusDadosCumprimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusDadosCumprimento
{
    /**
     * Constructor.
     *
     * @param SetorResource         $setorResource
     * @param EspecieTarefaResource $especieTarefaResource
     */
    public function __construct(
        protected readonly SetorResource $setorResource,
        protected readonly EspecieTarefaResource $especieTarefaResource,
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
        if (!$solicitacaoDTO->getTarefaDadosCumprimento()) {
            $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();
            $configSolicitacaoAutomatizada = $salarioMaternidadeRuralDriver->getConfigModuloSolicitacaoAutomatizada();
            $setorEntity = $this->setorResource->findOne(
                (int)$configSalarioMaternidadeRural['setor_dados_cumprimento']
            );
            $especieTarefaEntity = $this->especieTarefaResource->findOneBy([
                'nome' => $configSolicitacaoAutomatizada['especie_tarefa_dados_cumprimento'],
            ]);
            $tarefaEntity = $salarioMaternidadeRuralDriver->createTarefa(
                (new TarefaDTO())
                    ->setProcesso($solicitacaoEntity->getProcesso())
                    ->setDataHoraInicioPrazo(new DateTime())
                    ->setSetorResponsavel($setorEntity)
                    ->setEspecieTarefa($especieTarefaEntity),
                $transactionId
            );
            $solicitacaoDTO->setTarefaDadosCumprimento($tarefaEntity);
        }
    }
}
