<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Twig\Environment;

/**
 * AnaliseDossiesService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseDossiesService
{
    /**
     * Constructor.
     *
     * @param SetorResource      $setorResource
     * @param TransactionManager $transactionManager
     * @param Environment        $twig
     * @param JuntadaResource    $juntadaResource
     */
    public function __construct(
        protected readonly SetorResource $setorResource,
        protected readonly TransactionManager $transactionManager,
        protected readonly Environment $twig,
        protected readonly JuntadaResource $juntadaResource
    ) {
    }

    /**
     * Retorna as analises de dossies pela configuração informada.
     *
     * @param string         $cpf
     * @param DossieEntity[] $dossies
     * @param array          $config
     *
     * @return array
     */
    private function geraAnaliseDossies(
        string $cpf,
        array $dossies,
        array $config,
    ): array {
        return array_map(
            function (array $analiseConf) use ($cpf, $dossies) {
                return new AnaliseDossies(
                    $analiseConf['analise'],
                    $cpf,
                    array_map(
                        fn (DossieEntity $dossie) => $dossie->getId(),
                        array_filter(
                            $dossies,
                            fn (DossieEntity $dossie) => $dossie->getNumeroDocumentoPrincipal() === $cpf
                        )
                    )
                );
            },
            $config
        );
    }

    /**
     * Retorna as analises encapsuladas conforme configuração informada.
     *
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param DossieEntity[]                              $dossies
     * @param array                                       $configAnalise
     *
     * @return array
     */
    public function geraAnaliseRequisitos(
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        array $dossies,
        array $configAnalise,
    ): array {
        return [
            ...$this->geraAnaliseDossies(
                $dadosTipoSolicitacao->getCpfBeneficiario(),
                $dossies,
                $configAnalise['analises_beneficiario'],
            ),
            ...(
                !$dadosTipoSolicitacao->getCpfConjuge() ?
                    [] :
                    $this->geraAnaliseDossies(
                        $dadosTipoSolicitacao->getCpfConjuge(),
                        $dossies,
                        $configAnalise['analises_conjuge'],
                    )
            ),
        ];
    }

    /**
     *  Executa o fluxo geração da analise inicial dos dossies.
     *
     * @param SolicitacaoAutomatizadaDTO                  $solicitacaoDTO
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param array                                       $dossies
     * @param array                                       $configSalarioMaternidadeRural
     *
     * @return void
     */
    public function solicitaAnaliseInicialDossies(
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $dossies,
        array $configSalarioMaternidadeRural
    ): void {
        $dadosTipoSolicitacao->setAnaliseInicialDossiesSolicitada(true);
        $solicitacaoDTO
            ->setAnalisesDossies(
                $salarioMaternidadeRuralDriver->serializeAnaliseDossies(
                    $this->geraAnaliseRequisitos(
                        $dadosTipoSolicitacao,
                        $dossies,
                        $configSalarioMaternidadeRural['analise_inicial']
                    )
                )
            )
            ->setDadosTipoSolicitacao(
                $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $dadosTipoSolicitacao
                )
            )
            ->setStatus(StatusSolicitacaoAutomatizada::ANALISANDO_REQUISITOS);
    }

    /**
     * Executa o fluxo geração da analise de prova material dos dossies.
     *
     * @param SolicitacaoAutomatizadaDTO                  $solicitacaoDTO
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param array                                       $dossies
     * @param array                                       $analisesDossies
     * @param array                                       $configSalarioMaternidadeRural
     *
     * @return void
     */
    public function solicitaAnaliseProvaMaterialDossies(
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $dossies,
        array $analisesDossies,
        array $configSalarioMaternidadeRural,
    ): void {
        $dadosTipoSolicitacao->setAnaliseInicialDossiesSolicitada(true);
        $solicitacaoDTO
            ->setAnalisesDossies(
                $salarioMaternidadeRuralDriver->serializeAnaliseDossies(
                    [
                        ...$analisesDossies,
                        ...$this->geraAnaliseRequisitos(
                            $dadosTipoSolicitacao,
                            $dossies,
                            $configSalarioMaternidadeRural['analise_prova_material']
                        ),
                    ]
                )
            )
            ->setDadosTipoSolicitacao(
                $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $dadosTipoSolicitacao
                )
            )
            ->setStatus(StatusSolicitacaoAutomatizada::ANALISANDO_REQUISITOS);
    }

    /**
     * Verifica o resultado da analise dos dossies.
     *
     * @param SolicitacaoAutomatizadaEntity               $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO                  $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param array                                       $analisesDossies
     * @param array                                       $configSalarioMaternidadeRural
     * @param string                                      $transactionId
     *
     * @return void
     */
    public function processaResultadoAnaliseDossies(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $analisesDossies,
        array $configSalarioMaternidadeRural,
        string $transactionId,
    ): void {
        $this->juntaRelatorioAnalise(
            $solicitacaoDTO,
            $salarioMaternidadeRuralDriver,
            $analisesDossies,
            $configSalarioMaternidadeRural,
            $transactionId
        );
        $analisesDossiesComRestricoes = array_filter(
            $analisesDossies,
            fn (AnaliseDossies $analiseDossies) => !$analiseDossies->getPassouAnalise()
        );
        $analiseProcurador = false;
        if (!empty($analisesDossiesComRestricoes)) {
            $salarioMaternidadeRuralDriver->processaAnaliseRequisitosNegativa(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
            if ($configSalarioMaternidadeRural['analise_negativa_pelo_procurador'] === true) {
                $analiseProcurador = true;
            } else {
                $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::INDEFERIDO);
                $salarioMaternidadeRuralDriver->processaStatus(
                    $solicitacaoEntity,
                    $solicitacaoDTO,
                    $transactionId
                );
                return;
            }
        } else {
            $salarioMaternidadeRuralDriver->processaAnaliseRequisitosPositiva(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
            if ($configSalarioMaternidadeRural['analise_positiva_pelo_procurador'] === true) {
                $analiseProcurador = true;
            } else {
                $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::DEFERIDO);
                $salarioMaternidadeRuralDriver->processaStatus(
                    $solicitacaoEntity,
                    $solicitacaoDTO,
                    $transactionId
                );
                return;
            }
        }
        if ($analiseProcurador) {
            $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::ANALISE_PROCURADOR);
            $salarioMaternidadeRuralDriver->processaStatus(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
            $salarioMaternidadeRuralDriver->processaStatus(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
            return;
        }
    }

    /**
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver
     * @param array                         $analisesDossies
     * @param array                         $configSalarioMaternidadeRural
     * @param string                        $transactionId
     *
     * @return void
     */
    private function juntaRelatorioAnalise (
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        array $analisesDossies,
        array $configSalarioMaternidadeRural,
        string $transactionId
    ): void
    {
        // Gerar conteúdo do documento com $analisesDossies
        $template = 'Resources/SolicitacaoAutomatizada/SalarioMaternidadeRural/relatorio.html.twig';
        $dadosTemplate = [
            'processo' => $solicitacaoDTO->getProcesso(),
            'analises' => $analisesDossies
        ];
        $conteudo = "";
        if ($this->twig->getLoader()->exists($template)) {
            $conteudo = $this->twig->render(
                $template,
                $dadosTemplate
            );
        }
        if (!empty($conteudo)) {
            $setorOrigem = $this->setorResource->findOne(
                (int) $configSalarioMaternidadeRural['setor_analise_procurador']
            );
            $documentoEntity = $salarioMaternidadeRuralDriver->criarDocumento(
                $conteudo,
                $transactionId,
                $solicitacaoDTO->getProcesso(),
                null,
                $setorOrigem,
                true
            );
            $juntadaDTO = (new Juntada())
                ->setDocumento($documentoEntity)
                ->setDescricao('Relatório de análise dos dossiês');
            $this->juntadaResource->create($juntadaDTO, $transactionId);
        }
    }
}
