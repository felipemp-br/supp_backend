<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ExtracaoMetadadosErrorException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ResourceUnavailableException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores\ExtratorMetadadosDocumentoManager;
use SuppCore\AdministrativoBackend\Twig\AppExtension;

/**
 * ProcessaStatusDeferido.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusDeferido
{
    /**
     * Constructor.
     *
     * @param LoggerInterface                   $logger
     * @param AppExtension                      $appExtension
     * @param ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly AppExtension $appExtension,
        private readonly ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager,
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
        $solicitacaoDTO->setStatus(
            $configSalarioMaternidadeRural['dados_cumprimento_sumario'] ?
                StatusSolicitacaoAutomatizada::AGUARDANDO_CUMPRIMENTO
                : StatusSolicitacaoAutomatizada::DADOS_CUMPRIMENTO
        );
        $dadosTipoSolicitacao = $salarioMaternidadeRuralDriver->deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
            $solicitacaoDTO->getDadosTipoSolicitacao()
        );
        $dadosCumprimento = [
            'cpfBeneficiario' => $this->appExtension->formatCpf(
                $dadosTipoSolicitacao->getCpfBeneficiario()
            ),
            'dataNascimentoCrianca' => $dadosTipoSolicitacao->getDataNascimentoCrianca(),
            'nup' => $this->appExtension->formatNup($dadosTipoSolicitacao->getNup()),
            'der' => null,
            'nit' => null,
            'especie' => '80',
            'dat' => $dadosTipoSolicitacao->getDataNascimentoCrianca(),
            'tipoLicenca' => 'Parto',
            'aborto' => 'Não',
            'partoAntecipado' => 0,
            'dataLicenca' => $dadosTipoSolicitacao->getDataNascimentoCrianca(),
            'duracaoGestacao' => null,
            'cid' => null,
            'dib' => $dadosTipoSolicitacao->getDataNascimentoCrianca(),
            'dip' => $dadosTipoSolicitacao->getDataNascimentoCrianca(),
            'periodoReconhecidoExpresso' => false,
            'nomeEmpregador' => null,
            'dataInicioVinculo' => null,
            'dataFimVinculo' => null,
            'nb' => $dadosTipoSolicitacao->getNb(),
            'cep' => $solicitacaoEntity->getBeneficiario()
                ?->getEnderecos()
                ?->findFirst(fn (int $index, Endereco $endereco) => $endereco->getPrincipal())
                ?->getCep() ?? '',
        ];
        $dossies = $solicitacaoEntity->getDossies()->toArray();
        foreach ($configSalarioMaternidadeRural['extracoes_dados_cumprimento'] as $extrator) {
            try {
                $dadosCumprimento = array_merge(
                    $dadosCumprimento,
                    $this->extratorMetadadosDocumentoManager->extrairMetadados(
                        $extrator['extrator'],
                        $dossies,
                        $dadosTipoSolicitacao
                    )
                );
            } catch (ExtracaoMetadadosErrorException|ResourceUnavailableException $e) {
                $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::DADOS_CUMPRIMENTO);
                $this->logger->error(
                    sprintf(
                        'Erro ao extrair dados de cumprimento da solicitação automatizada %s',
                        $solicitacaoDTO->getId()
                    ),
                    [
                        'solicitacao_automatizada_id' => $solicitacaoDTO->getId(),
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'trace' => $e->getTraceAsString(),
                    ]
                );
            }
        }
        $solicitacaoDTO->setDadosCumprimento(
            json_encode(
                $dadosCumprimento,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
        $salarioMaternidadeRuralDriver->processaStatus(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $transactionId
        );
    }
}
