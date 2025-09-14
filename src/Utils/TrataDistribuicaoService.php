<?php

declare(strict_types=1);
/**
 * /src/Utils/TrataDistribuicaoService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Exception;
use Psr\Log\LoggerInterface;
use Redis;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Helpers\DigitoDistribuicao\DigitoDistribuicaoInterface;
use SuppCore\AdministrativoBackend\Helpers\DigitoDistribuicao\DigitoDistribuicaoProcesso;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * Class TrataDistribuicaoService.
 *
 * Trata a distribuição de tarefas conforme regras estabelecidas.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TrataDistribuicaoService implements TrataDistribuicaoServiceInterface
{
    private EntityInterface $setor;
    private Tarefa $tarefa;
    private array $cache = [];
    private array $stats;
    private array $usuariosDistribuicao = [];
    private bool $apenasDistribuicaoAutomatica;
    private int $divergenciaMaxima;
    private int $prazoEqualizacao;

    public function __construct(
        private readonly AfastamentoResource $afastamentoResource,
        private readonly LoggerInterface $logger,
        private readonly LotacaoRepository $lotacaoRepository,
        private readonly TarefaRepository $tarefaRepository,
        private readonly VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        #[AutowireIterator('supp_backend.digito_distribuicao')]
        protected iterable $digitoDistribuicaoInterfaces,
    ) {
    }

    /**
     * @throws Exception
     */
    public function tratarDistribuicaoAutomatica(
        Tarefa $tarefa,
        array $usuarios
    ):void {
        try {
            $this->tarefa = $tarefa;
            $this->setor = $tarefa->getSetorResponsavel();

            $this->stats = [];
            $this->prazoEqualizacao = $this->setor->getPrazoEqualizacao() ?? 7;
            $this->divergenciaMaxima = $this->setor->getDivergenciaMaxima() ?? 10;

            $this->apenasDistribuicaoAutomatica = $this->setor->getApenasDistribuicaoAutomatica() ?? true;
            $comPrevencaoRelativa = $this->setor->getComPrevencaoRelativa() ?? true;

            $this->stats['setor'][$this->setor->getId()]['nome'] = $this->tarefa->getSetorResponsavel()->getNome();
            $this->stats['setor'][$this->setor->getId()]['prazoEqualizacao'] = $this->prazoEqualizacao;
            $this->stats['setor'][$this->setor->getId()]['divergenciaMaxima'] = $this->divergenciaMaxima;
            $this->stats['setor'][$this->setor->getId()]['apenasDistribuicaoAutomatica'] = $this->apenasDistribuicaoAutomatica;
            $this->stats['setor'][$this->setor->getId()]['totalDiasTrabalhadosPeriodoEqualizacao'] = 0;
            $this->stats['setor'][$this->setor->getId()]['totalDistribuicoesPeriodoEqualizacao'] = 0;
            $this->stats['setor'][$this->setor->getId()]['mediaDistribuicoesPorDiaTrabalhadoPeriodoEqualizacao'] = 0;

            if (!isset($this->cache[$this->setor->getId()])) {
                $this->cache[$this->setor->getId()] = [];
            }

            if (!isset($this->cache[$this->setor->getId()]['usuariosDistribuicao']) ||
                empty($this->cache[$this->setor->getId()]['usuariosDistribuicao'])) {
                $this->usuariosDistribuicao = $this->afastamentoResource->limpaListaUsuariosAfastados(
                    $usuarios,
                    $this->tarefa->getDataHoraFinalPrazo()
                );
                $this->cache[$this->setor->getId()]['usuariosDistribuicao'] = $this->usuariosDistribuicao;
            } else {
                $this->usuariosDistribuicao = $this->cache[$this->setor->getId()]['usuariosDistribuicao'];
            }

            $usuariosIds = [];

            // É redistribuição para o mesmo setor?
            $redistribuicao = null !== $this->tarefa->getId();
            if ($redistribuicao) {
                $tarefaEntity = $this->tarefaRepository->find($this->tarefa->getId());
                if ($this->setor->getId() === $tarefaEntity->getSetorResponsavel()->getId()) {
                    //remove o responsável pela tarefa da lista de distribuição
                    $this->usuariosDistribuicao = array_filter(
                        $this->usuariosDistribuicao,
                        function (Usuario $usuario) use ($tarefaEntity) {
                            return $usuario->getId() !== $tarefaEntity->getUsuarioResponsavel()->getId();
                        }
                    );
                }
            }

            foreach ($usuarios as $usuario) {
                if (!in_array($usuario, $this->usuariosDistribuicao)) {
                    $this->stats['usuariosAfastados'][$usuario->getId()]['nome'] = $usuario->getNome();
                } else {
                    $this->stats['usuariosDisponiveis'][$usuario->getId()]['nome'] = $usuario->getNome();
                    $usuariosIds[$usuario->getId()] = $usuario;
                }
            }

            $this->usuariosDistribuicao = array_values($this->usuariosDistribuicao);

            if (!count($this->usuariosDistribuicao)) {
                throw new RuntimeException('Não há usuário apto a receber tarefa neste setor!');
            }

            $tratouDistribuicao = false;
            // Regra 1 e 2
            if ($this->distribuicaoPrevencaoAbsolutaNUP(array_keys($usuariosIds))) {
                $tratouDistribuicao = true;
            }

            // Regra 3
            if (false === $tratouDistribuicao && $this->distribuicaoPrevencaoAbsolutaDigitoCentena()) {
                $tratouDistribuicao = true;
            }

            if (false === $tratouDistribuicao) {
                // Faz o balancemanto de carga
                $usuariosIdsAposMaximaDivergencia = $this->balanceamentoDeCarga();
            }

            // Regra 4
            if (false === $tratouDistribuicao &&
                $comPrevencaoRelativa && $this->distribuicaoPrevencaoRelativaNUP(
                    array_keys($usuariosIdsAposMaximaDivergencia)
                )) {
                $tratouDistribuicao = true;
            }

            // Regra 5
            if (false === $tratouDistribuicao) {
                $this->distribuicaoMenorMedia($usuariosIdsAposMaximaDivergencia, $usuariosIds);
            }

            $tarefa->setDistribuicaoAutomatica(true);
            $tarefa->setAuditoriaDistribuicao(json_encode($this->stats));

            $this->cache[$this->setor->getId()]['distribuicoes'][] = [
                'usuarioResponsavel' => $this->tarefa->getUsuarioResponsavel(),
                'NUP' => $this->tarefa->getProcesso()->getNUP(),
            ];

            if ($tarefa->getLivreBalanceamento()) {
                $this->cache[$this->setor->getId()]['total_distribuicoes'][$tarefa->getUsuarioResponsavel()->getId()]['total_lb'] =
                    ($this->cache[$this->setor->getId()]['total_distribuicoes'][$tarefa->getUsuarioResponsavel()->getId()]['total_lb'] ?? 0) + 1;
            }

            $suffix = '';
            if ($this->apenasDistribuicaoAutomatica) {
                $suffix = '_au';
            }

            $this->cache[$this->setor->getId()]['total_distribuicoes'][$tarefa->getUsuarioResponsavel()->getId()]['total'.$suffix] =
                ($this->cache[$this->setor->getId()]['total_distribuicoes'][$tarefa->getUsuarioResponsavel()->getId()]['total'.$suffix] ?? 0) + 1;
        } catch (Exception $exception) {
            $message = $this->tarefa->getId()
                ? sprintf('Tarefa Id %s : %s', $this->tarefa->getId(), $exception->getMessage())
                : $exception->getMessage();

            $this->logger->error('TrataDistribuicaoServiceError: ' . $message);
            throw new Exception($message);
        }
    }

    /**
     * Regra 1.
     *
     * Prevenção absoluta por tarefa aberta no NUP ou em NUP vinculado!
     *
     * Atenção, temos que verificar o banco, mas também o presente ciclo de execução!
     */
    private function distribuicaoPrevencaoAbsolutaNUP(
        array $usuariosIds
    ): bool {
        $usuarioPreferenciaAbsoluta = null;

        // Verifica o cache de prevenção absoluta
        foreach ($this->cache[$this->setor->getId()]['distribuicoes'] ?? [] as $distribuicao) {
            if ($distribuicao['NUP'] === $this->tarefa->getProcesso()->getNUP()) {
                $usuarioPreferenciaAbsoluta = $distribuicao['usuarioResponsavel'];
                break;
            }
        }

        // Busca por preferência absoluta caso não encontrada no cache.
        $usuarioPreferenciaAbsoluta ??= $this->tarefaRepository->findPreferenciaAbsoluta(
            $usuariosIds,
            $this->tarefa->getProcesso()->getId(),
            $this->tarefa->getSetorResponsavel()->getId()
        );

        if ($usuarioPreferenciaAbsoluta) {
            $this->stats['PREVENCAO_ABSOLUTA_TAREFA_NUP'][$usuarioPreferenciaAbsoluta->getId(
            )]['nome'] = $usuarioPreferenciaAbsoluta->getNome();
            $this->tarefa->setUsuarioResponsavel($usuarioPreferenciaAbsoluta);
            $this->tarefa->setTipoDistribuicao(self::TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_TAREFA_NUP);

            return true;
        } else {
            $processosVinculados = [];

            /** @var VinculacaoProcesso $vinculacoesProcesso */
            foreach ($this->tarefa->getProcesso()->getVinculacoesProcessos() as $vinculacoesProcesso) {
                $processosVinculados[] = $vinculacoesProcesso->getProcessoVinculado();
            }

            /** @var VinculacaoProcesso $vinculacaoProcesso */
            $vinculacaoProcesso = $this->vinculacaoProcessoRepository->findByProcessoVinculado(
                $this->tarefa->getProcesso()->getId()
            );

            if ($vinculacaoProcesso) {
                $processosVinculados[] = $vinculacaoProcesso->getProcesso();
            }

            // Preferência absoluta por tarefa aberta nos NUPs vinculados
            foreach ($processosVinculados as $processoVinculado) {
                $usuarioPreferenciaAbsoluta = null;

                // Verifica o cache de prevenção absoluta
                foreach ($this->cache[$this->setor->getId()]['distribuicoes'] ?? [] as $distribuicao) {
                    if ($distribuicao['NUP'] === $processoVinculado->getNUP()) {
                        $usuarioPreferenciaAbsoluta = $distribuicao['usuarioResponsavel'];
                        break;
                    }
                }

                // Busca por preferência absoluta caso não encontrada no cache.
                $usuarioPreferenciaAbsoluta ??= $this->tarefaRepository->findPreferenciaAbsoluta(
                    $usuariosIds,
                    $processoVinculado->getId(),
                    $this->setor->getId()
                );
                if ($usuarioPreferenciaAbsoluta) {
                    $this->tarefa->setUsuarioResponsavel($usuarioPreferenciaAbsoluta);
                    $this->stats['PREVENCAO_ABSOLUTA_TAREFA_NUP_VINCULADO'][$usuarioPreferenciaAbsoluta->getId(
                    )]['nome'] = $usuarioPreferenciaAbsoluta->getNome();
                    $this->stats['PREVENCAO_ABSOLUTA_TAREFA_NUP_VINCULADO'][$usuarioPreferenciaAbsoluta->getId(
                    )]['NUPVinculado'] = $processoVinculado->getNUP();
                    $this->tarefa->setTipoDistribuicao(
                        self::TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_TAREFA_NUP_VINCULADO
                    );
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Regra 2.
     *
     * Prevenção absoluta por dígito ou centena atribuida na lotação
     */
    private function distribuicaoPrevencaoAbsolutaDigitoCentena(): bool
    {
        $usuariosComDigito = [];

        // Processa dígitos
        foreach ($this->usuariosDistribuicao as $usuario) {
            $lotacao = $this->lotacaoRepository->findLotacaoBySetorAndColaborador(
                $this->setor->getId(),
                $usuario->getColaborador()->getId()
            );
            if ($this->setor->getDistribuicaoCentena()) {
                $digitosUsuario = $this->processaDigitosDistribuicao($lotacao->getCentenasDistribuicao());
                $this->stats['centenaNUP'] =  (int) $this->getDigitoDistribuicaoInterface($this->tarefa->getProcesso())
                    ->getCentena($this->tarefa->getProcesso());
                // (int) substr($tarefa->getProcesso()->getNUP(), 9, 2);
                if (in_array($this->stats['centenaNUP'], $digitosUsuario, true)) {
                    $usuariosComDigito[] = $usuario;
                    $this->stats['usuariosComCentenaNUP'][$usuario->getId()]['nome'] = $usuario->getNome();
                }
            } else {
                $digitosUsuario = $this->processaDigitosDistribuicao($lotacao->getDigitosDistribuicao());
                $this->stats['digitoNUP'] = (int) $this->getDigitoDistribuicaoInterface($this->tarefa->getProcesso())
                    ->getDezena($this->tarefa->getProcesso());
                // (int) substr($tarefa->getProcesso()->getNUP(), 10, 1);
                if (in_array($this->stats['digitoNUP'], $digitosUsuario, true)) {
                    $usuariosComDigito[] = $usuario;
                    $this->stats['usuariosComDigitoNUP'][$usuario->getId()]['nome'] = $usuario->getNome();
                }
            }
        }

        // Tem apenas um usuário no dígito, é dele
        // Final do processamento
        if (1 === count($usuariosComDigito)) {
            $this->tarefa->setUsuarioResponsavel($usuariosComDigito[0]);
            $this->stats['PREVENCAO_ABSOLUTA_DIGITO_CENTENA'][$usuariosComDigito[0]->getId(
            )]['nome'] = $usuariosComDigito[0]->getNome();
            $this->tarefa->setTipoDistribuicao(self::TIPO_DISTRIBUICAO_PREVENCAO_ABSOLUTA_DIGITO_CENTENA);
            return true;
        }

        // Tem mais de um, distribui balanceando entre eles
        if (count($usuariosComDigito) > 1) {
            $this->usuariosDistribuicao = $usuariosComDigito;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function balanceamentoDeCarga(): array
    {
        // um array com as quantidades de distribuições que os usuários receberam no período de equalizacao
        $distribuicoesUsuariosPeriodoEqualizacao = [];

        // um array com as quantidades de dias trabalhados pelos usuários
        $diasTrabalhadosUsuario = [];

        // um array com as médias de distribuições por dia trabalhado
        $mediaUsuarios = [];

        // quantos dígitos livres cada usuário já recebeu hoje?
        // a divergência máxima opera para limitar distorções diárias
        $distribuicoesUsuariosHoje = [];

        // total de dias trabalhados de todos
        $totalDiasTrabalhados = 0;

        // total de distribuições recebidas por todos
        $totalDistribuicoesPeriodoEqualizacao = 0;

        foreach ($this->usuariosDistribuicao as $usuario) {
            $usuarioId = $usuario->getId();
            $distribuicoesUsuariosPeriodoEqualizacao[$usuario->getId()] = 0;
            $distribuicoesUsuariosHoje[$usuario->getId()] = 0;

            $suffix = '';
            if ($this->apenasDistribuicaoAutomatica) {
                $suffix = '_au';
            }

            // Verifica se a quantidade de distribuições do usuário estão armazenadas na distribuição em bloco.
            if (isset($this->cache[$this->setor->getId()]['total_distribuicoes'][$usuarioId])) {
                $distribuicoesUsuariosPeriodoEqualizacao[$usuarioId] =
                    $this->cache[$this->setor->getId()]['total_distribuicoes'][$usuarioId]['total'.$suffix];
                $distribuicoesUsuariosHoje[$usuario->getId()] =
                    $this->cache[$this->setor->getId()]['total_distribuicoes'][$usuarioId]['total_lb'];
            } else {
                // Total de distribuições do usuário no período do prazo de equalização.
                $distribuicoesUsuariosPeriodoEqualizacao[$usuario->getId(
                )] = $this->tarefaRepository->findQuantidadeDistribuicoes(
                    $usuarioId,
                    $this->setor->getId(),
                    $this->apenasDistribuicaoAutomatica,
                    $this->prazoEqualizacao
                );

                $this->cache[$this->setor->getId()]['total_distribuicoes'][$usuarioId]['total'.$suffix] =
                    $distribuicoesUsuariosPeriodoEqualizacao[$usuario->getId()];

                // Total de distribuições do usuário de livre balanceamento no dia
                $distribuicoesUsuariosHoje[$usuario->getId(
                )] = $this->tarefaRepository->findQuantidadeDistribuicoesLivresHoje(
                    $usuarioId,
                    $this->setor->getId()
                );

                $this->cache[$this->setor->getId()]['total_distribuicoes'][$usuarioId]['total_lb'] =
                    $distribuicoesUsuariosHoje[$usuario->getId()];
            }

            $this->stats['usuariosDisponiveis'][$usuario->getId(
            )]['quantidadeDistribuicoesPeriodoEqualizacao'] = $distribuicoesUsuariosPeriodoEqualizacao[$usuarioId];
            $this->stats['usuariosDisponiveis'][$usuario->getId(
            )]['quantidadeDistribuicoesLivresHoje'] = $distribuicoesUsuariosHoje[$usuarioId];

            $diasTrabalhadosUsuario[
            $usuario->getId()
            ] = ($this->prazoEqualizacao - $this->afastamentoResource->getRepository()->findDiasAfastamento(
                $usuario->getColaborador()->getId(),
                $this->prazoEqualizacao
            ));

            $this->stats['usuariosDisponiveis'][
            $usuario->getId()
            ]['diasTrabalhadosPeriodoEqualizacao'] = $diasTrabalhadosUsuario[$usuarioId];

            $totalDiasTrabalhados += $diasTrabalhadosUsuario[$usuario->getId()];
            $this->stats['setor'][$this->setor->getId()]['totalDiasTrabalhadosPeriodoEqualizacao'] = $totalDiasTrabalhados;

            $totalDistribuicoesPeriodoEqualizacao += $distribuicoesUsuariosPeriodoEqualizacao[$usuario->getId()];
            $this->stats['setor'][$this->setor->getId(
            )]['totalDistribuicoesPeriodoEqualizacao'] = $totalDistribuicoesPeriodoEqualizacao;
        }

        // acha a média
        if ($totalDiasTrabalhados) {
            $mediaDistribuicoesPorDiaTrabalhado = $totalDistribuicoesPeriodoEqualizacao / $totalDiasTrabalhados;
        } else {
            $mediaDistribuicoesPorDiaTrabalhado = 0;
        }
        $this->stats['setor'][$this->setor->getId(
        )]['mediaDistribuicoesPorDiaTrabalhadoPeriodoEqualizacao'] = $mediaDistribuicoesPorDiaTrabalhado;

        $pesos = [];

        // ajustes e ponderações de afastamento e correção do peso da lotacao
        foreach ($this->usuariosDistribuicao as $usuario) {
            $usuarioId = $usuario->getId();
            // primeiro a média do usuário
            // se o usuário não tem nenhum dia trabalhado no período de equalização,
            // damos a ele a média do setor
            if ($diasTrabalhadosUsuario[$usuario->getId()]) {
                $mediaUsuarios[$usuarioId] = $distribuicoesUsuariosPeriodoEqualizacao[
                    $usuario->getId()
                    ] / $diasTrabalhadosUsuario[$usuarioId];
            } else {
                $mediaUsuarios[$usuario->getId()] = $mediaDistribuicoesPorDiaTrabalhado;
            }
            $this->stats['usuariosDisponiveis'][$usuario->getId(
            )]['mediaDistribuicoesPorDiaTrabalhadoPeriodoEqualizacao'] = $mediaUsuarios[$usuarioId];

            $peso = $this->lotacaoRepository->findLotacaoBySetorAndColaborador(
                $this->setor->getId(),
                $usuario->getColaborador()->getId()
            )->getPeso();
            $this->stats['usuariosDisponiveis'][$usuario->getId()]['peso'] = $peso;
            $pesos[$usuario->getId()] = $peso;

            // ajuste do peso sobre a média
            $distribuicoesUsuariosPeriodoEqualizacao[$usuario->getId()] *= 100 / $peso;
            $mediaUsuarios[$usuario->getId()] *= 100 / $peso;
            $this->stats['usuariosDisponiveis'][$usuario->getId(
            )]['mediaDistribuicoesPorDiaTrabalhadoPeriodoEqualizacaoComPeso'] = $mediaUsuarios[$usuarioId];
        }

        $usuariosIdDisponiveisAposMaximaDivergencia = [];

        // enquanto houver usuários sem distribuição de livre balanceamento no dia
        // apenas eles participam
        foreach ($distribuicoesUsuariosHoje as $usuarioId => $distribuicaoUsuarioHoje) {
            if (!$distribuicaoUsuarioHoje) {
                $usuariosIdDisponiveisAposMaximaDivergencia[$usuarioId] = $mediaUsuarios[$usuarioId];
                $this->stats['usuariosDisponiveisLivreBalanceamento'][$usuarioId] = $this->stats['usuariosDisponiveis'][$usuarioId];
            }
        }

        // precisamos eliminar os usuarios que não passam
        // no teste da máxima divergencia diária

        // não temos mais usuário com zero distribuições livres no dia,
        // hora de ativar a divergência máxima
        if (!count($usuariosIdDisponiveisAposMaximaDivergencia)) {
            // a primeira coisa a se fazer é aplicar o peso
            $distribuicoesUsuariosHojeComPeso = [];

            foreach ($distribuicoesUsuariosHoje as $usuarioId => $distribuicaoUsuarioHoje) {
                $distribuicoesUsuariosHojeComPeso[$usuarioId] = $distribuicaoUsuarioHoje * 100 / $pesos[$usuarioId];
            }

            // vamos ordenar da menor para a maior distribuicao diaria até aqui
            asort($distribuicoesUsuariosHojeComPeso);
            reset($distribuicoesUsuariosHojeComPeso);

            $menorDistribuicaoHojeComPeso = $distribuicoesUsuariosHojeComPeso[key($distribuicoesUsuariosHojeComPeso)];

            $this->stats['menorQuantidadeDistribuicacaoLivreBalanceamentoHoje'] = $menorDistribuicaoHojeComPeso;

            foreach ($distribuicoesUsuariosHojeComPeso as $usuarioId => $distribuicaoUsuarioHojeComPeso) {
                $divergencia = (($distribuicaoUsuarioHojeComPeso * 100) / $menorDistribuicaoHojeComPeso) - 100;
                if ($divergencia <= $this->divergenciaMaxima) {
                    $usuariosIdDisponiveisAposMaximaDivergencia[$usuarioId] = $mediaUsuarios[$usuarioId];
                    $this->stats[
                    'usuariosDisponiveisLivreBalanceamento'
                    ][$usuarioId] = $this->stats['usuariosDisponiveis'][$usuarioId];
                    $this->stats['usuariosDisponiveisLivreBalanceamento'][$usuarioId]['divergencia'] = $divergencia;
                } else {
                    $this->stats['usuariosQueExcederamDivergenciaMaxima']
                    [$usuarioId] = $this->stats['usuariosDisponiveis'][$usuarioId];
                    $this->stats['usuariosQueExcederamDivergenciaMaxima'][$usuarioId]['divergencia'] = $divergencia;
                    $this->stats['usuariosQueExcederamDivergenciaMaxima']['divergenciaMaxima'] = $this->divergenciaMaxima;
                }
            }
        }

        return $usuariosIdDisponiveisAposMaximaDivergencia;
    }

    /**
     * Regra 4.
     *
     * Prevenção absoluta por dígito ou centena atribuida na lotação
     */
    private function distribuicaoPrevencaoRelativaNUP(
        array $usuariosIds
    ): bool {
        $usuarioComPreferencia = $this->tarefaRepository->findPreferencia(
            $usuariosIds,
            $this->tarefa->getProcesso()->getId()
        );

        if ($usuarioComPreferencia) {
            $this->tarefa->setUsuarioResponsavel($usuarioComPreferencia);
            $this->tarefa->setLivreBalanceamento(true);
            $this->stats['PREVENCAO_RELATIVA_TAREFA_NUP'][$usuarioComPreferencia->getId(
            )]['nome'] = $usuarioComPreferencia->getNome();
            $this->tarefa->setTipoDistribuicao(self::TIPO_DISTRIBUICAO_PREVENCAO_RELATIVA_TAREFA_NUP);
            return true;
        }
        return false;
    }

    /**
     * Regra 5.
     *
     * Prevenção absoluta por dígito ou centena atribuida na lotação
     */
    private function distribuicaoMenorMedia(
        array $usuariosIdDisponiveisAposMaximaDivergencia,
        array $usuariosId,
    ): void {
        $usuariosIdDisponiveisAposMaximaDivergenciaMenoresMediasDistribuicao = array_keys(
            $usuariosIdDisponiveisAposMaximaDivergencia,
            min($usuariosIdDisponiveisAposMaximaDivergencia)
        );
        $usuarioIdDisponivelAposMaximaDivergenciaMenorMediaDistribuicao =
            $usuariosIdDisponiveisAposMaximaDivergenciaMenoresMediasDistribuicao[
            random_int(0, count($usuariosIdDisponiveisAposMaximaDivergenciaMenoresMediasDistribuicao) - 1)
            ];
        $this->tarefa->setUsuarioResponsavel(
            $usuariosId[$usuarioIdDisponivelAposMaximaDivergenciaMenorMediaDistribuicao]
        );
        $this->tarefa->setLivreBalanceamento(true);
        $this->stats['livreDistribuicaoMenorMedia'][
        $usuarioIdDisponivelAposMaximaDivergenciaMenorMediaDistribuicao
        ]['nome'] = $usuariosId[$usuarioIdDisponivelAposMaximaDivergenciaMenorMediaDistribuicao]->getNome();
        $this->tarefa->setTipoDistribuicao(self::TIPO_DISTRIBUICAO_MENOR_MEDIA);
    }

    /**
     * @param $expressao
     * @return array
     */
    private function processaDigitosDistribuicao($expressao): array
    {
        $digitos = [];
        if (!$expressao) {
            return $digitos;
        }
        $intervalos = explode(',', $expressao);
        foreach ($intervalos as $intervalo) {
            $inicioFim = explode('-', $intervalo);
            if (count($inicioFim) > 1) {
                $max = max($inicioFim);
                for ($j = min($inicioFim); $j <= $max; ++$j) {
                    $digitos[] = (int) $j;
                }
            } else {
                $digitos[] = (int) $inicioFim[0];
            }
        }

        return $digitos;
    }

    /**
     * @param Processo $processo
     * @return DigitoDistribuicaoInterface
     * @throws Exception
     */
    private function getDigitoDistribuicaoInterface(Processo $processo) : DigitoDistribuicaoInterface
    {
        foreach ($this->digitoDistribuicaoInterfaces as $digitoDistribuicaoInterface) {
            // a interface padrao sempre sera do administrativo
            if ($digitoDistribuicaoInterface instanceof DigitoDistribuicaoProcesso) {
                $digitoDistribuicaoPadrao = $digitoDistribuicaoInterface;
            }

            if ($digitoDistribuicaoInterface->supports($processo)) {
                return $digitoDistribuicaoInterface;
            }
        }

        return $digitoDistribuicaoPadrao;
    }
}
