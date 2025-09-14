<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use SuppCore\AdministrativoBackend\Security\InternalLogInService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * ProcessaStatusAnaliseProcurador.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusAnaliseProcurador
{
    /**
     * Constructor.
     *
     * @param SetorResource              $setorResource
     * @param EspecieTarefaResource      $especieTarefaResource
     * @param TransactionManager         $transactionManager
     * @param InternalLogInService       $internalLogInService
     * @param EtiquetaResource           $etiquetaResource
     * @param VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
     */
    public function __construct(
        protected readonly SetorResource $setorResource,
        protected readonly EspecieTarefaResource $especieTarefaResource,
        protected readonly TransactionManager $transactionManager,
        protected readonly InternalLogInService $internalLogInService,
        protected readonly EtiquetaResource $etiquetaResource,
        protected readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
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
        if (!$solicitacaoDTO->getTarefaAnalise()) {
            $dadosTipoSolicitacao = $salarioMaternidadeRuralDriver
                ->deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $solicitacaoDTO->getDadosTipoSolicitacao()
                );
            $analisesDossies = $salarioMaternidadeRuralDriver->deserializeAnaliseDossies(
                $solicitacaoDTO->getAnalisesDossies()
            );
            $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();
            $config = $salarioMaternidadeRuralDriver->getConfigModuloSolicitacaoAutomatizada();
            $setorEntity = $this->setorResource->findOne(
                (int) $configSalarioMaternidadeRural['setor_analise_procurador']
            );
            $tarefaEntity = $salarioMaternidadeRuralDriver->createTarefa(
                (new TarefaDTO())
                    ->setDataHoraInicioPrazo(new DateTime())
                    ->setProcesso($solicitacaoEntity->getProcesso())
                    ->setSetorResponsavel($setorEntity)
                    ->setEspecieTarefa(
                        $this->especieTarefaResource->findOneBy([
                            'nome' => $config['especie_tarefa_analise'],
                        ])
                    )
                    ->setObservacao(
                        $this->getObservacoesAnalisesDossies($analisesDossies)
                    ),
                $transactionId
            );
            $solicitacaoDTO->setTarefaAnalise($tarefaEntity);
            $persistEntities = $this->transactionManager->getToPersistEntities($transactionId);
            /** @var TarefaEntity $tarefaCriada */
            $tarefaCriada = current(
                array_filter(
                    $persistEntities,
                    fn ($p) => $p instanceof TarefaEntity
                )
            );
            // impersonate com o usuário responsável para que o modelo seja preenchido corretamente
            $this->internalLogInService->logUserIn($tarefaCriada->getUsuarioResponsavel());
            $this->vinculaEtiquetasAnaliseTarefaProcurador(
                $tarefaEntity,
                $dadosTipoSolicitacao,
                $configSalarioMaternidadeRural['analise_inicial'],
                $analisesDossies,
                $transactionId
            );
            if ($dadosTipoSolicitacao->getAnaliseProvaMaterialDossiesSolicitada()) {
                $this->vinculaEtiquetasAnaliseTarefaProcurador(
                    $tarefaEntity,
                    $dadosTipoSolicitacao,
                    $configSalarioMaternidadeRural['analise_prova_material'],
                    $analisesDossies,
                    $transactionId
                );
            }
        }
    }

    /**
     * Vincula as etiquetas a tarefa de análise do procurador conforme resultado da analise de requisitos.
     *
     * @param TarefaEntity                                $tarefaEntity
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param array                                       $configAnalise
     * @param AnaliseDossies[]                            $analisesDossies
     * @param string                                      $transactionId
     *
     * @return void
     */
    private function vinculaEtiquetasAnaliseTarefaProcurador(
        TarefaEntity $tarefaEntity,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        array $configAnalise,
        array $analisesDossies,
        string $transactionId
    ): void {
        $cpfBeneficiario = $dadosTipoSolicitacao->getCpfBeneficiario();
        $cpfConjuge = $dadosTipoSolicitacao->getCpfConjuge();
        $etiquetasUsadas = [];
        $this->criaVinculacoesEtiquetasAnaliseTarefaProcurador(
            $tarefaEntity,
            $configAnalise['analises_beneficiario'],
            array_filter(
                $analisesDossies,
                fn (AnaliseDossies $analiseDossies) => $analiseDossies->getCpfAnalisado() === $cpfBeneficiario
            ),
            $etiquetasUsadas,
            $transactionId
        );
        if ($cpfConjuge) {
            $this->criaVinculacoesEtiquetasAnaliseTarefaProcurador(
                $tarefaEntity,
                $configAnalise['analises_conjuge'],
                array_filter(
                    $analisesDossies,
                    fn (AnaliseDossies $analiseDossies) => $analiseDossies->getCpfAnalisado() === $cpfConjuge
                ),
                $etiquetasUsadas,
                $transactionId
            );
        }
    }

    /**
     * @param TarefaEntity $tarefaEntity
     * @param array        $configuracaoAnalises
     * @param array        $analisesDossies
     * @param array        $etiquetasUsadas
     * @param string       $transactionId
     *
     * @return void
     */
    private function criaVinculacoesEtiquetasAnaliseTarefaProcurador(
        TarefaEntity $tarefaEntity,
        array $configuracaoAnalises,
        array $analisesDossies,
        array &$etiquetasUsadas,
        string $transactionId
    ): void {
        foreach ($configuracaoAnalises as $configuracaoAnalise) {
            foreach ($analisesDossies as $analiseDossies) {
                if ($configuracaoAnalise['analise'] === $analiseDossies->getAnalise()) {
                    foreach ($configuracaoAnalise['etiquetas_tarefa_analise_procurador'] as $confEtiquetas) {
                        if ($confEtiquetas['passou_analise'] === $analiseDossies->getPassouAnalise()) {
                            $etiquetasId = array_diff($confEtiquetas['etiquetas'], $etiquetasUsadas);
                            if (!empty($etiquetasId)) {
                                $etiquetas = $this->etiquetaResource->find(
                                    [
                                        'id' => sprintf(
                                            'in:%s',
                                            implode(',', $etiquetasId)
                                        ),
                                        'modalidadeEtiqueta.valor' => 'eq:TAREFA',
                                    ],
                                    limit: 999,
                                    offset: 0
                                );
                                foreach ($etiquetas['entities'] as $etiqueta) {
                                    $etiquetasUsadas[] = $etiqueta->getId();
                                    $this->vinculacaoEtiquetaResource->create(
                                        (new VinculacaoEtiquetaDTO())
                                            ->setEtiqueta($etiqueta)
                                            ->setTarefa($tarefaEntity),
                                        $transactionId
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Retorna as observações das analises dos dossies.
     *
     * @param array $analisesDossies
     *
     * @return string
     */
    private function getObservacoesAnalisesDossies(
        array $analisesDossies
    ): string {
        $observacao = implode(
            '; ',
            array_map(
                fn (AnaliseDossies $analiseDossies) => $analiseDossies->getObservacao(),
                array_filter(
                    $analisesDossies,
                    fn (AnaliseDossies $analiseDossies) => !!$analiseDossies->getObservacao()
                )
            )
        );
        if (mb_strlen($observacao) > 255) {
            return mb_strimwidth($observacao, 0, 255 - 3, '...');
        }

        return $observacao;
    }
}
