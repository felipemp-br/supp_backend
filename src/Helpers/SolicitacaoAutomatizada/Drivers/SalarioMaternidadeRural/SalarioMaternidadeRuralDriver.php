<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural;

use Override;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\AbstractSolicitacaoAutomatizadaDriver;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusAguardandoCumprimento;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusAnaliseProcurador;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusAndamento;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusCriada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusDadosCumprimento;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusDeferido;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusErroSolicitacao;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services\ProcessaStatusIndeferido;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SolicitacaoAutomatizadaDriverInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * StatusCriadaDriver.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SalarioMaternidadeRuralDriver extends AbstractSolicitacaoAutomatizadaDriver implements SolicitacaoAutomatizadaDriverInterface
{
    protected readonly ProcessaStatusCriada $processaStatusCriadaService;
    protected readonly ProcessaStatusAndamento $processaStatusAndamentoService;
    protected readonly ProcessaStatusDeferido $processaStatusDeferidoService;
    protected readonly ProcessaStatusIndeferido $processaStatusIndeferidoService;
    protected readonly ProcessaStatusAguardandoCumprimento $processaStatusAguardandoCumprimentoService;
    protected readonly ProcessaStatusDadosCumprimento $processaStatusDadosCumprimentoService;
    protected readonly ProcessaStatusErroSolicitacao $processaStatusErroSolicitacaoService;
    protected readonly ProcessaStatusAnaliseProcurador $processaStatusAnaliseProcuradorService;

    /**
     * Seta as dependências adicionais da classe.
     *
     * @param ProcessaStatusCriada                $processaStatusCriadaService
     * @param ProcessaStatusAndamento             $processaStatusAndamentoService
     * @param ProcessaStatusDeferido              $processaStatusDeferidoService
     * @param ProcessaStatusIndeferido            $processaStatusIndeferidoService
     * @param ProcessaStatusAguardandoCumprimento $processaStatusAguardandoCumprimentoService
     * @param ProcessaStatusDadosCumprimento      $processaStatusDadosCumprimentoService
     * @param ProcessaStatusErroSolicitacao       $processaStatusErroSolicitacaoService
     * @param ProcessaStatusAnaliseProcurador     $processaStatusAnaliseProcuradorService
     *
     * @return void
     */
    #[Required]
    public function setDriverStatusProcessors(
        ProcessaStatusCriada $processaStatusCriadaService,
        ProcessaStatusAndamento $processaStatusAndamentoService,
        ProcessaStatusDeferido $processaStatusDeferidoService,
        ProcessaStatusIndeferido $processaStatusIndeferidoService,
        ProcessaStatusAguardandoCumprimento $processaStatusAguardandoCumprimentoService,
        ProcessaStatusDadosCumprimento $processaStatusDadosCumprimentoService,
        ProcessaStatusErroSolicitacao $processaStatusErroSolicitacaoService,
        ProcessaStatusAnaliseProcurador $processaStatusAnaliseProcuradorService,
    ): void {
        $this->processaStatusCriadaService = $processaStatusCriadaService;
        $this->processaStatusAndamentoService = $processaStatusAndamentoService;
        $this->processaStatusDeferidoService = $processaStatusDeferidoService;
        $this->processaStatusIndeferidoService = $processaStatusIndeferidoService;
        $this->processaStatusAguardandoCumprimentoService = $processaStatusAguardandoCumprimentoService;
        $this->processaStatusDadosCumprimentoService = $processaStatusDadosCumprimentoService;
        $this->processaStatusErroSolicitacaoService = $processaStatusErroSolicitacaoService;
        $this->processaStatusAnaliseProcuradorService = $processaStatusAnaliseProcuradorService;
    }

    /**
     * Verifica se o handler suporta o tipo de solicitacao automatizada.
     *
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     *
     * @return bool
     */
    public function supports(
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
    ): bool {
        return $this->hasConfigModulo()
            && $tipoSolicitacaoAutomatizada->getSigla() === self::getSiglaTipoSolicitacaoAutomatizada();
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusCriada(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusCriadaService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusErroSolicitacao(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusErroSolicitacaoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusAndamento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusAndamentoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusAnaliseProcurador(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusAnaliseProcuradorService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusDeferido(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusDeferidoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusIndeferido(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusIndeferidoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusDadosCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusDadosCumprimentoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    #[Override]
    protected function processaStatusAguardandoCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->processaStatusAguardandoCumprimentoService->handle(
            $solicitacaoEntity,
            $solicitacaoDTO,
            $this,
            $transactionId
        );
    }

    /**
     * Retorna o nome do config modulo.
     *
     * @return string
     */
    public function getNomeConfigModulo(): string
    {
        return 'supp_core.administrativo_backend.solicitacao_automatizada.salario_maternidade_rural';
    }

    /**
     * Retorna as configurações do modulo.
     *
     * @return array
     */
    public function getConfigModulo(): array
    {
        return $this->suppParameterBag->get($this->getNomeConfigModulo());
    }

    /**
     * Retorna se o config modulo está configurado.
     *
     * @return bool
     */
    public function hasConfigModulo(): bool
    {
        return $this->suppParameterBag->has($this->getNomeConfigModulo());
    }

    /**
     * Retorna a sigla do tipo de solicitacao automatizada.
     *
     * @return string
     */
    public static function getSiglaTipoSolicitacaoAutomatizada(): string
    {
        return 'PACIFICA_SAL_MAT_RURAL';
    }

    /**
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacaoSalarioMaternidadeRural
     *
     * @return string
     */
    public function serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacaoSalarioMaternidadeRural
    ): string {
        return $this->serializer->serialize(
            $dadosTipoSolicitacaoSalarioMaternidadeRural,
            'json'
        );
    }

    /**
     * @param string $jsonString
     *
     * @return DadosTipoSolicitacaoSalarioMaternidadeRural
     */
    public function deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
        string $jsonString
    ): DadosTipoSolicitacaoSalarioMaternidadeRural {
        return $this->serializer->deserialize(
            $jsonString,
            DadosTipoSolicitacaoSalarioMaternidadeRural::class,
            'json'
        );
    }
}
