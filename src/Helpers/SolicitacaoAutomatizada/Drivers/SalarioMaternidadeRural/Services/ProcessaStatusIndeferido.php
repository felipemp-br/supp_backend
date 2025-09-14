<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use Twig\Environment;

/**
 * ProcessaStatusIndeferido.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusIndeferido
{
    public function __construct(
        protected readonly Environment $twig,
        protected readonly JuntadaResource $juntadaResource,
        protected readonly VolumeRepository $volumeRepository,
    ) {}

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
        $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::SOLICITACAO_NAO_ATENDIDA);
        $dadosTipoSolicitacao = $salarioMaternidadeRuralDriver
            ->deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                $solicitacaoDTO->getDadosTipoSolicitacao()
            );
        // Gerar despacho de Indeferimento
        $template = 'Resources/SolicitacaoAutomatizada/SalarioMaternidadeRural/indeferimento.html.twig';
        $dadosTemplate = [
            'processo' => $solicitacaoDTO->getProcesso(),
            'beneficiario' => $solicitacaoDTO->getBeneficiario(),
            'dadosSolicitacao' => $dadosTipoSolicitacao
        ];
        $conteudo = "";
        if ($this->twig->getLoader()->exists($template)) {
            $conteudo = $this->twig->render(
                $template,
                $dadosTemplate
            );
        }
        if (!empty($conteudo)) {

            $documentoEntity = $salarioMaternidadeRuralDriver->criarDocumento(
                $conteudo,
                $transactionId
            );
            $juntadaDTO = (new Juntada())
                ->setDocumento($documentoEntity)
                ->setDescricao('Indeferimento de solicitação')
                ->setVolume($this->volumeRepository->findVolumeAbertoByProcessoId(
                    $solicitacaoDTO->getProcesso()->getId())
                );
            $this->juntadaResource->create($juntadaDTO, $transactionId);

            $solicitacaoDTO->setResultadoSolicitacao(
                $salarioMaternidadeRuralDriver->criarDocumento(
                    $conteudo,
                    $transactionId,
                    $solicitacaoDTO->getProcesso()
                )
            );
        }

        $salarioMaternidadeRuralDriver->processaStatus(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $transactionId
        );
    }
}
