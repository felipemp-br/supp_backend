<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\DossiesNecessarios;

/**
 * ProcessaStatusCriada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusCriada
{
    /**
     * Constructor.
     *
     * @param PessoaResource $pessoaResource
     */
    public function __construct(
        private readonly PessoaResource $pessoaResource
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
        $config = $salarioMaternidadeRuralDriver->getConfigModulo();
        /** @var DadosFormulario $dadosFormulario */
        $dadosFormulario = $solicitacaoDTO->getDadosFormulario();
        $data = json_decode(
            $dadosFormulario->getDataValue(),
            true
        );
        $cpfBeneficiario = $salarioMaternidadeRuralDriver::somenteNumeros($data['cpfBeneficiario']);
        $cpfCrianca = $salarioMaternidadeRuralDriver::somenteNumeros($data['cpfCrianca']);
        $cpfConjuge = !empty($data['cpfConjuge'])
            ? $salarioMaternidadeRuralDriver::somenteNumeros($data['cpfConjuge']) : null;

        $criancaEntity = $this->pessoaResource->findPessoaAdvanced(
            $cpfCrianca,
            $transactionId
        );

        $solicitacaoDTO
            ->setDossiesNecessarios(
                $salarioMaternidadeRuralDriver->serializeDossiesNecessarios(
                    [
                        new DossiesNecessarios($cpfBeneficiario, $config['dossies_beneficiario']),
                    ]
                )
            )
            ->setDadosTipoSolicitacao(
                $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    new DadosTipoSolicitacaoSalarioMaternidadeRural(
                        $salarioMaternidadeRuralDriver::somenteNumeros($data['numeroBeneficioNegado']),
                        $cpfBeneficiario,
                        $cpfCrianca,
                        $criancaEntity->getDataNascimento()->format('Y-m-d'),
                        $data['dataRequerimentoAdministrativo'],
                        $solicitacaoDTO->getProcesso()->getNUP(),
                        $cpfConjuge,
                        false,
                        true,
                        false,
                        false,
                    )
                )
            )
            ->setStatus(StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES);
    }
}
