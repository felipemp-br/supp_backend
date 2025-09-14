<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\SolicitacaoAutomatizadaExceptionInterface;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\UnsupportedExtratorMetadadosDocumentosException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores\ExtratorMetadadosDocumentoManager;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\DossiesNecessarios;

/**
 * ProcessaStatusAndamento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusAndamento
{
    /**
     * Constructor.
     *
     * @param ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager
     * @param AnaliseDossiesService             $analiseDossiesService
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        protected readonly ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager,
        protected readonly AnaliseDossiesService $analiseDossiesService,
        protected readonly LoggerInterface $logger,
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
     *
     */
    public function handle(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId
    ): void {
        try {
            $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();
            $dadosTipoSolicitacao = $salarioMaternidadeRuralDriver
                ->deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $solicitacaoDTO->getDadosTipoSolicitacao()
                );
            $analisesDossies = $salarioMaternidadeRuralDriver->deserializeAnaliseDossies(
                $solicitacaoDTO->getAnalisesDossies()
            );
            $analisesDossiesComRestricoes = array_filter(
                $analisesDossies,
                fn (AnaliseDossies $analiseDossies) => !$analiseDossies->getPassouAnalise()
            );
            switch (true) {
                case !$dadosTipoSolicitacao->getExtracoesConjugeExecutada():
                    $this->executaExtracoesConjuge(
                        $solicitacaoEntity,
                        $dadosTipoSolicitacao,
                        $salarioMaternidadeRuralDriver,
                        $configSalarioMaternidadeRural['extracoes_conjuge']
                    );
                    $dadosTipoSolicitacao->setExtracoesConjugeExecutada(true);
                    $solicitacaoDTO->setDadosTipoSolicitacao(
                        $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                            $dadosTipoSolicitacao
                        )
                    );
                    // já que a intenção é executar os dois blocos de código, deve estar fora do case
                    if ($dadosTipoSolicitacao->getCpfConjuge()
                        && !$dadosTipoSolicitacao->getDossiesConjugeSolicitados()) {
                        $this->solicitaDossiesConjuge(
                            $solicitacaoDTO,
                            $dadosTipoSolicitacao,
                            $salarioMaternidadeRuralDriver,
                            $configSalarioMaternidadeRural,
                        );
                    }
                    break;
                case !$dadosTipoSolicitacao->getAnaliseInicialDossiesSolicitada():
                    $this->analiseDossiesService->solicitaAnaliseInicialDossies(
                        $solicitacaoDTO,
                        $dadosTipoSolicitacao,
                        $salarioMaternidadeRuralDriver,
                        $solicitacaoEntity->getDossies()->toArray(),
                        $configSalarioMaternidadeRural,
                    );
                    break;
                case empty($analisesDossiesComRestricoes)
                && !$dadosTipoSolicitacao->getAnaliseProvaMaterialDossiesSolicitada():
                    $this->analiseDossiesService->solicitaAnaliseProvaMaterialDossies(
                        $solicitacaoDTO,
                        $dadosTipoSolicitacao,
                        $salarioMaternidadeRuralDriver,
                        $solicitacaoEntity->getDossies()->toArray(),
                        $analisesDossies,
                        $configSalarioMaternidadeRural,
                    );
                    break;
                default:
                    $this->analiseDossiesService->processaResultadoAnaliseDossies(
                        $solicitacaoEntity,
                        $solicitacaoDTO,
                        $salarioMaternidadeRuralDriver,
                        $analisesDossies,
                        $configSalarioMaternidadeRural,
                        $transactionId
                    );
                    break;
            }

            return;
        } catch (SolicitacaoAutomatizadaExceptionInterface $e) {
            $this->logger->error(
                sprintf(
                    'Erro ao processar andamento da solicitação automatizada %s',
                    $solicitacaoEntity->getId()
                ),
                [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]
            );
            $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO);
            $salarioMaternidadeRuralDriver->processaStatus(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
        }
    }

    /**
     * Executa o fluxo de solicitação de dossies do conjuge.
     *
     * @param SolicitacaoAutomatizadaDTO                  $solicitacaoDTO
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param array                                       $configSalarioMaternidadeRural
     *
     * @return void
     */
    protected function solicitaDossiesConjuge(
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $configSalarioMaternidadeRural,
    ): void {
        $dossiesNecessarios = $salarioMaternidadeRuralDriver->deserializeDossiesNecessarios(
            $solicitacaoDTO->getDossiesNecessarios()
        );
        $dossiesNecessarios[] = new DossiesNecessarios(
            $dadosTipoSolicitacao->getCpfConjuge(),
            $configSalarioMaternidadeRural['dossies_conjuge']
        );
        $dadosTipoSolicitacao->setDossiesConjugeSolicitados(true);
        $solicitacaoDTO
            ->setDossiesNecessarios(
                $salarioMaternidadeRuralDriver->serializeDossiesNecessarios($dossiesNecessarios)
            )
            ->setDadosTipoSolicitacao(
                $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $dadosTipoSolicitacao
                )
            )
            ->setStatus(StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES);
    }

    /**
     * Verifica a extração de dados do conjuge.
     *
     * @param SolicitacaoAutomatizadaEntity               $solicitacaoEntity
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param array                                       $extracoes
     *
     * @return void
     *
     * @throws UnsupportedExtratorMetadadosDocumentosException
     */
    private function executaExtracoesConjuge(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $extracoes
    ): void {
        $cpfBeneficiario = $dadosTipoSolicitacao->getCpfBeneficiario();
        foreach ($extracoes as $extracaoDadosConjuge) {
            $dadosExtracao = $this->extratorMetadadosDocumentoManager->extrairMetadados(
                $extracaoDadosConjuge['extrator'],
                array_filter(
                    $solicitacaoEntity->getDossies()->toArray(),
                    fn (DossieEntity $dossie) => $dossie->getNumeroDocumentoPrincipal() === $cpfBeneficiario
                ),
                $dadosTipoSolicitacao
            );
            if (isset($dadosExtracao['conjuge'], $dadosExtracao['cpf_conjuge'])
                && $dadosExtracao['conjuge'] && $dadosExtracao['cpf_conjuge']) {
                $cpfExtraido = $salarioMaternidadeRuralDriver::somenteNumeros($dadosExtracao['cpf_conjuge']);
                if ($cpfExtraido === $dadosTipoSolicitacao->getCpfBeneficiario()) {
                    continue;
                }
                if ($dadosTipoSolicitacao->getCpfConjuge() && $dadosTipoSolicitacao->getCpfConjuge() !== $cpfExtraido) {
                    $sigla = $salarioMaternidadeRuralDriver::getSiglaTipoSolicitacaoAutomatizada();
                    $this->logger->info(
                        sprintf(
                            'Foi identificada uma divergência de CPF de conjuge para a solicitação automatizada %s.',
                            $solicitacaoEntity->getId()
                        ),
                        [
                            'solicitacao_automatizada_id' => $solicitacaoEntity->getId(),
                            'sigla_tipo_solicitacao_automatizada' => $sigla,
                            'cpf_formulario' => $dadosTipoSolicitacao->getCpfConjuge(),
                            'cpf_extraido' => $cpfExtraido,
                        ]
                    );
                    $dadosTipoSolicitacao->setDivergenciaConjuge(true);
                }

                $dadosTipoSolicitacao->setCpfConjuge($cpfExtraido);
                break;
            }
        }
    }
}
