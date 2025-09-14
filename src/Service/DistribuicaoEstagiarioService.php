<?php

declare(strict_types=1);
/**
 * /src/Service/DistribuicaoEstagiarioService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Service;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\ColaboradorRepository;
use SuppCore\AdministrativoBackend\Repository\GeneroTarefaRepository;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DistribuicaoEstagiarioService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuicaoEstagiarioService
{
    private EntityManagerInterface $entityManager;
    private TarefaRepository $tarefaRepository;
    private ColaboradorRepository $colaboradorRepository;
    private GeneroTarefaRepository $generoTarefaRepository;
    private UsuarioRepository $usuarioRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TarefaRepository $tarefaRepository,
        ColaboradorRepository $colaboradorRepository,
        GeneroTarefaRepository $generoTarefaRepository,
        UsuarioRepository $usuarioRepository
    ) {
        $this->entityManager = $entityManager;
        $this->tarefaRepository = $tarefaRepository;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->generoTarefaRepository = $generoTarefaRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Executa a distribuição de tarefas para os estagiários selecionados
     *
     * @param array $estagiariosIds Array de IDs dos usuários estagiários
     * @param Usuario $usuarioExecutor Usuário que está executando a distribuição
     * @param Setor $setor Setor do usuário executor
     * @param int|null $unidadeId ID da unidade (opcional)
     * @param int|null $generoId ID do gênero de tarefa (contexto específico)
     * 
     * @return array Resultado da distribuição
     * 
     * @throws Exception
     */
    public function executarDistribuicaoEstagiarios(
        array $estagiariosIds,
        Usuario $usuarioExecutor,
        Setor $setor,
        ?int $unidadeId = null,
        ?int $generoId = null
    ): array {
        error_log("======================================================");
        error_log("=== INÍCIO DA DISTRIBUIÇÃO DE ESTAGIÁRIOS (FILA CIRCULAR) ===");
        error_log("======================================================");
        error_log("Executor: " . $usuarioExecutor->getNome() . " (ID: " . $usuarioExecutor->getId() . ")");
        error_log("Setor: " . $setor->getId());
        error_log("Estagiários IDs: " . implode(', ', $estagiariosIds));
        error_log("Unidade ID: " . ($unidadeId ?? 'NULL'));
        error_log("Gênero ID: " . ($generoId ?? 'NULL'));
       
        // Stack trace para ver de onde está sendo chamado
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        error_log("Chamado de: " . ($trace[1]['class'] ?? 'N/A') . '::' . ($trace[1]['function'] ?? 'N/A'));
      
        // Validar entrada
        if (empty($estagiariosIds)) {
            throw new Exception('Pelo menos um estagiário deve ser selecionado');
        }

        // Buscar estagiários válidos
        $estagiarios = $this->buscarEstagiarios($estagiariosIds, $setor, $unidadeId);
        
        if (empty($estagiarios)) {
            throw new Exception('Nenhum estagiário válido encontrado');
        }

   
        if ($generoId) {
            $generoTarefa = $this->generoTarefaRepository->find($generoId);
            if (!$generoTarefa || !$generoTarefa->getAtivo()) {
                 throw new Exception('Gênero de tarefa não encontrado ou inativo');
            }
            $generosTarefas = [$generoTarefa];
        } else {
            // Apenas quando não há contexto específico, buscar todos os gêneros ativos
            $generosTarefas = $this->generoTarefaRepository->findBy(['ativo' => true]);
            foreach ($generosTarefas as $gt) {
                error_log("   - " . $gt->getNome() . " (ID: " . $gt->getId() . ")");
            }
        }
        
        $distribuicoesRealizadas = [];
        $totalTarefas = 0;

        $this->entityManager->beginTransaction();
        
        try {
            foreach ($generosTarefas as $generoTarefa) {
                
                $resultado = $this->distribuirTarefasPorGenero(
                    $generoTarefa,
                    $estagiarios,
                    $setor,
                    $usuarioExecutor
                );
                
                if ($resultado['tarefas_distribuidas'] > 0) {
                    $distribuicoesRealizadas[] = [
                        'genero_tarefa' => $generoTarefa->getNome(),
                        'tarefas_distribuidas' => $resultado['tarefas_distribuidas'],
                        'estagiarios_contemplados' => $resultado['estagiarios_contemplados']
                    ];
                    $totalTarefas += $resultado['tarefas_distribuidas'];
                    
                    error_log("✅ DISTRIBUIÇÃO CONCLUÍDA para " . $generoTarefa->getNome() . ":");
                    error_log("   📄 Tarefas distribuídas: " . $resultado['tarefas_distribuidas']);
                    error_log("   👥 Estagiários contemplados: " . $resultado['estagiarios_contemplados']);
                } else {
                    error_log("⚠️ Nenhuma tarefa pendente encontrada para o gênero: " . $generoTarefa->getNome());
                }
            }

            $this->entityManager->commit();
            
            error_log("======================================================");
            error_log("🎉 === DISTRIBUIÇÃO CONCLUÍDA COM SUCESSO === 🎉");
            error_log("📈 RESUMO FINAL:");
            error_log("   📄 Total de tarefas distribuídas: " . $totalTarefas);
            error_log("   📊 Distribuições por gênero: " . count($distribuicoesRealizadas));
            foreach ($distribuicoesRealizadas as $dist) {
                error_log("     🎯 {$dist['genero_tarefa']}: {$dist['tarefas_distribuidas']} tarefas para {$dist['estagiarios_contemplados']} estagiários");
            }
            error_log("🔄 Algoritmo aplicado: Fila Circular Sequencial");
            error_log("🎯 Princípio: Último que recebeu vai para o final da fila");
            error_log("======================================================");

            return [
                'success' => true,
                'distribuicoes_realizadas' => $distribuicoesRealizadas,
                'total_tarefas' => $totalTarefas,
                'criterios_aplicados' => ['fila_circular', 'sequencial', 'ultimo_recebeu']
            ];

        } catch (Exception $e) {
            $this->entityManager->rollback();
            throw new Exception('Erro durante a distribuição: ' . $e->getMessage());
        }
    }

    /**
     * Distribui tarefas para um gênero específico
     */
    private function distribuirTarefasPorGenero(
        GeneroTarefa $generoTarefa,
        array $estagiarios,
        Setor $setor,
        Usuario $usuarioExecutor
    ): array {
        // Buscar tarefas pendentes para o gênero
        error_log("🔍 Buscando tarefas pendentes para o gênero: " . $generoTarefa->getNome() . " (ID: " . $generoTarefa->getId() . ")");
        $tarefasPendentes = $this->findTarefasPendentesEstagiarios($generoTarefa->getId(), $setor->getId());
        
        if (empty($tarefasPendentes)) {
            error_log("⚠️ Nenhuma tarefa pendente encontrada para distribuição");
            return ['tarefas_distribuidas' => 0, 'estagiarios_contemplados' => 0];
        }
        
        error_log("📄 Total de tarefas pendentes encontradas: " . count($tarefasPendentes));
        foreach ($tarefasPendentes as $idx => $tarefa) {
            error_log("   Tarefa " . ($idx + 1) . ": ID " . $tarefa->getId() . " (Criada em: " . $tarefa->getCriadoEm()->format('Y-m-d H:i:s') . ")");
        }

        // Aplicar algoritmo de distribuição circular
        error_log("🔄 Aplicando algoritmo de fila circular para gênero: " . $generoTarefa->getNome());
        $estagiarios = $this->aplicarAlgoritmoDistribuicao($estagiarios, $generoTarefa);
        
        error_log("📋 FILA INICIAL ESTABELECIDA:");
        foreach ($estagiarios as $index => $colaborador) {
            error_log("   " . ($index + 1) . "º na fila: " . $colaborador->getUsuario()->getNome() . " (ID: " . $colaborador->getUsuario()->getId() . ")");
        }
        
        $tarefasDistribuidas = 0;
        $estagiariosContemplados = [];

        foreach ($tarefasPendentes as $index => $tarefa) {
            error_log("=== DISTRIBUINDO TAREFA " . ($index + 1) . "/" . count($tarefasPendentes) . " ===");
            error_log("Tarefa ID: " . $tarefa->getId());
            
            // Mostrar estado atual da fila antes da distribuição
            error_log("📊 ESTADO ATUAL DA FILA (antes da tarefa " . ($index + 1) . "):");
            foreach ($estagiarios as $pos => $colabFila) {
                $indicador = $pos === 0 ? "👉" : "  ";
                error_log("   {$indicador} Posição " . ($pos + 1) . ": " . $colabFila->getUsuario()->getNome());
            }
            
            // Selecionar próximo estagiário da fila circular
            $estagiario = $this->selecionarProximoEstagiario($estagiarios, $generoTarefa);
            
            if ($estagiario && $this->validarDistribuicao($tarefa, $estagiario)) {
                error_log("Distribuindo para: " . $estagiario->getUsuario()->getNome() . " (ID: " . $estagiario->getUsuario()->getId() . ")");
                
                $this->executarDistribuicao($tarefa, $estagiario, $setor, $usuarioExecutor, $generoTarefa);
                $tarefasDistribuidas++;
                
                if (!in_array($estagiario->getId(), $estagiariosContemplados)) {
                    $estagiariosContemplados[] = $estagiario->getId();
                }
                
                // Rotacionar a fila após distribuição bem-sucedida
                error_log("🔄 ROTACIONANDO FILA: " . $estagiario->getUsuario()->getNome() . " vai para o final");
                $this->rotacionarEstagiarios($estagiarios);
                
                error_log("📊 NOVA ORDEM DA FILA (após rotação):");
                foreach ($estagiarios as $pos => $colabFila) {
                    $indicador = $pos === 0 ? "👉" : "  ";
                    error_log("   {$indicador} Posição " . ($pos + 1) . ": " . $colabFila->getUsuario()->getNome());
                }
                
                error_log("✅ Tarefa distribuída com sucesso!");
            } else {
                error_log("FALHA na distribuição da tarefa ID: " . $tarefa->getId());
                if (!$estagiario) {
                    error_log("  - Nenhum estagiário disponível");
                } else {
                    error_log("❌ Validação falhou para: " . $estagiario->getUsuario()->getNome());
                    error_log("   Possíveis motivos: tarefa redistribuída, estagiário inativo, ou tarefa já concluída");
                    // Se a validação falhou, rotacionar para tentar o próximo
                    error_log("🔄 Tentando próximo estagiário da fila...");
                    $this->rotacionarEstagiarios($estagiarios);
                }
            }
        }

        return [
            'tarefas_distribuidas' => $tarefasDistribuidas,
            'estagiarios_contemplados' => count($estagiariosContemplados)
        ];
    }

    /**
     * Busca estagiários válidos para distribuição
     */
    private function buscarEstagiarios(array $estagiariosIds, Setor $setor, ?int $unidadeId): array
    {
        error_log("=== DEBUG: Buscando estagiários ===");
        error_log("IDs solicitados: " . implode(', ', $estagiariosIds));
        error_log("Setor ID: " . $setor->getId());
        error_log("Unidade ID: " . ($unidadeId ?? 'NULL'));

        // Primeiro, vamos verificar todos os colaboradores dos IDs solicitados (sem filtro de modalidade)
        $qbTest = $this->colaboradorRepository->createQueryBuilder('c_test');
        $qbTest->select('c_test', 'u_test', 'mc_test')
               ->join('c_test.usuario', 'u_test')
               ->join('c_test.modalidadeColaborador', 'mc_test')
               ->where('u_test.id IN (:ids)')
               ->setParameter('ids', $estagiariosIds);
        
        $testResult = $qbTest->getQuery()->getResult();
        error_log("=== Verificando modalidades dos usuários ===");
        foreach ($testResult as $colaborador) {
            error_log("Usuário: " . $colaborador->getUsuario()->getNome() . " - Modalidade: " . $colaborador->getModalidadeColaborador()->getValor());
        }

        $qb = $this->colaboradorRepository->createQueryBuilder('c');
        $qb->join('c.usuario', 'u')
           ->join('c.modalidadeColaborador', 'mc')
           ->join('c.lotacoes', 'l')
           ->join('l.setor', 's')
           ->where('u.id IN (:ids)')
           ->andWhere('u.enabled = true')
           ->andWhere('c.ativo = true')
           ->andWhere('mc.valor = :modalidade')
           ->andWhere('s.id = :setorId')
           ->setParameter('ids', $estagiariosIds)
           ->setParameter('modalidade', 'ESTAGIÁRIO')
           ->setParameter('setorId', $setor->getId())
           ->orderBy('u.nome', 'ASC');

        if ($unidadeId) {
            $qb->join('s.unidade', 'un')
               ->andWhere('un.id = :unidadeId')
               ->setParameter('unidadeId', $unidadeId);
        }

        $sql = $qb->getQuery()->getSQL();
        error_log("SQL gerada: " . $sql);

        $result = $qb->getQuery()->getResult();
        error_log("Estagiários encontrados: " . count($result));
        
        if (empty($result)) {
            // Vamos fazer uma query mais simples para debug
            error_log("=== DEBUG: Testando query sem filtro de setor/lotação ===");
            $qbSimple = $this->colaboradorRepository->createQueryBuilder('c_simple');
            $qbSimple->join('c_simple.usuario', 'u_simple')
                     ->join('c_simple.modalidadeColaborador', 'mc_simple')
                     ->where('u_simple.id IN (:ids)')
                     ->andWhere('u_simple.enabled = true')
                     ->andWhere('c_simple.ativo = true')
                     ->andWhere('mc_simple.valor = :modalidade')
                     ->setParameter('ids', $estagiariosIds)
                     ->setParameter('modalidade', 'ESTAGIÁRIO');
                     
            $simpleResult = $qbSimple->getQuery()->getResult();
            error_log("Estagiários encontrados sem filtro de setor: " . count($simpleResult));
            
            foreach ($simpleResult as $colaborador) {
                error_log("Colaborador sem filtro: " . $colaborador->getUsuario()->getNome());
            }
        }
        
        foreach ($result as $colaborador) {
            error_log("Colaborador encontrado: " . $colaborador->getUsuario()->getNome() . " (ID: " . $colaborador->getUsuario()->getId() . ")");
        }

        return $result;
    }

    /**
     * Aplica o algoritmo de distribuição circular sequencial
     * ALGORITMO CORRIGIDO: Inicia fila após o último que recebeu tarefa, seguindo ordem alfabética
     */
    private function aplicarAlgoritmoDistribuicao(array $estagiarios, GeneroTarefa $generoTarefa): array
    {  
        error_log("=== INICIANDO ALGORITMO CIRCULAR SEQUENCIAL CORRIGIDO ===");
        error_log("Gênero: " . $generoTarefa->getNome() . " (ID: " . $generoTarefa->getId() . ")");
        error_log("Total de estagiários: " . count($estagiarios));
        
        // PASSO 1: Ordenar estagiarios por ordem alfabética (ordem base)
        usort($estagiarios, function ($a, $b) {
            return strcasecmp($a->getUsuario()->getNome(), $b->getUsuario()->getNome());
        });
        
        error_log("🎯 ORDEM ALFABÉTICA BASE:");
        foreach ($estagiarios as $index => $colaborador) {
            error_log("   " . ($index + 1) . "º: " . $colaborador->getUsuario()->getNome());
        }
        
        // PASSO 2: Encontrar quem foi o ÚLTIMO de TODOS a receber tarefa para este gênero
        $ultimoQueRecebeu = $this->encontrarUltimoEstagiarioQueRecebeuTarefa(
            array_map(fn($c) => $c->getUsuario()->getId(), $estagiarios),
            $generoTarefa->getId()
        );
        
        if ($ultimoQueRecebeu) {
            error_log("🔍 ÚLTIMO que recebeu tarefa: " . $ultimoQueRecebeu['nome'] . 
                     " em " . $ultimoQueRecebeu['data']->format('Y-m-d H:i:s'));
            
            // PASSO 3: Reorganizar fila para começar APÓS o último que recebeu
            $filaReorganizada = $this->reorganizarFilaAposUltimo($estagiarios, $ultimoQueRecebeu['usuario_id']);
            
            error_log("🔄 FILA REORGANIZADA (começa após o último):");
            foreach ($filaReorganizada as $index => $colaborador) {
                $indicador = $index === 0 ? "👉" : "  ";
                error_log("   {$indicador} " . ($index + 1) . "º: " . $colaborador->getUsuario()->getNome());
            }
            
            return $filaReorganizada;
            
        } else {
            error_log("🎆 Primeira distribuição para este gênero - usando ordem alfabética");
            
            error_log("📋 FILA INICIAL (ordem alfabética):");
            foreach ($estagiarios as $index => $colaborador) {
                $indicador = $index === 0 ? "👉" : "  ";
                error_log("   {$indicador} " . ($index + 1) . "º: " . $colaborador->getUsuario()->getNome());
            }
            
            return $estagiarios;
        }
    }

    /**
     * Seleciona o próximo estagiário seguindo a fila circular
     */
    private function selecionarProximoEstagiario(array $estagiarios, GeneroTarefa $generoTarefa): ?Colaborador
    {
        if (empty($estagiarios)) {
            return null;
        }
        
        // Na fila circular, sempre pega o primeiro da lista ordenada
        $proximoEstagiario = $estagiarios[0];
        
        error_log("✅ Próximo estagiário selecionado: " . $proximoEstagiario->getUsuario()->getNome() . " (ID: " . $proximoEstagiario->getUsuario()->getId() . ")");
        
        return $proximoEstagiario;
    }

    /**
     * Rotaciona a lista de estagiários após uma distribuição (move o que recebeu para o final)
     */
    private function rotacionarEstagiarios(array &$estagiarios): void
    {
        if (count($estagiarios) > 1) {
            $primeiro = array_shift($estagiarios);
            $estagiarios[] = $primeiro;
            
            error_log("🔄 Fila rotacionada. Próximo na fila: " . (!empty($estagiarios) ? $estagiarios[0]->getUsuario()->getNome() : 'N/A'));
        }
    }

    /**
     * Valida se a distribuição pode ser realizada
     */
    private function validarDistribuicao(Tarefa $tarefa, Colaborador $estagiario): bool
    {
        // Verificar se a tarefa não foi redistribuída
        if ($tarefa->getRedistribuida()) {
            return false;
        }

        // Verificar se o estagiário está ativo
        if (!$estagiario->getAtivo() || !$estagiario->getUsuario()->getEnabled()) {
            return false;
        }

        // Verificar se a tarefa já não tem conclusão
        if ($tarefa->getDataHoraConclusaoPrazo() !== null) {
            return false;
        }

        return true;
    }

    /**
     * Executa a distribuição da tarefa para o estagiário
     * SIMPLIFICADO: Atualiza apenas ad_tarefa.estagiario_responsavel_id
     */
    private function executarDistribuicao(
        Tarefa $tarefa,
        Colaborador $estagiario,
        Setor $setor,
        Usuario $usuarioExecutor,
        GeneroTarefa $generoTarefa
    ): void {
        $agora = new DateTime();

        error_log("🎯 Atribuindo tarefa " . $tarefa->getId() . " ao estagiário: " . $estagiario->getUsuario()->getNome());

        // Atualizar APENAS a tarefa com o estagiário responsável
        $tarefa->setEstagiarioResponsavel($estagiario->getUsuario());
        $tarefa->setDataHoraDistribuicao($agora); // Mantido - necessário para rastrear quando foi distribuído
        // $tarefa->setDistribuicaoAutomatica(false); // Comentado - campo usado para outra lógica
        // $tarefa->setTipoDistribuicao(4); // Comentado - não necessário para distribuição de estagiário
        $tarefa->setDistribuicaoEstagiarioAutomatica(true); // Marca como distribuição automática de estagiário
        $tarefa->setAuditoriaDistribuicao(
            sprintf(
                'Distribuição estagiário (fila circular) - Executado por: %s - Gênero: %s',
                $usuarioExecutor->getNome(),
                $generoTarefa->getNome()
            )
        );

        // NÃO criar registro na tabela ad_distribuicao (isso é para distribuição entre usuários responsáveis)
        
        error_log("💾 Persistindo tarefa no banco de dados...");
        $this->entityManager->persist($tarefa);
        $this->entityManager->flush();
        
        // Registrar auditoria simplificada
        $this->registrarDistribuicaoAuditoria($tarefa, 'fila_circular_estagiarios');
        
        error_log("✨ Distribuição de estagiário concluída: Tarefa " . $tarefa->getId() . " → " . $estagiario->getUsuario()->getNome());
        error_log("📅 Data/hora da distribuição: " . $agora->format('Y-m-d H:i:s'));
    }

    /**
     * Registra auditoria simplificada da distribuição de estagiário
     */
    private function registrarDistribuicaoAuditoria(Tarefa $tarefa, string $criterio): void
    {
        // Log da operação para auditoria (apenas para estagiários)
        error_log(sprintf(
            '📋 AUDITORIA - Tarefa ID %d atribuída ao estagiário ID %d - Algoritmo: %s',
            $tarefa->getId(),
            $tarefa->getEstagiarioResponsavel()->getId(),
            $criterio
        ));
    }

    
    /**
     * Encontra o ÚLTIMO estagiário (entre todos os fornecidos) que recebeu tarefa para o gênero
     * SIMPLIFICADO: Usa apenas ad_tarefa.estagiario_responsavel_id
     */
    private function encontrarUltimoEstagiarioQueRecebeuTarefa(array $usuarioIds, int $generoId): ?array
    {
        error_log("🔍 Procurando último estagiário que recebeu tarefa entre os IDs: " . implode(', ', $usuarioIds));
        
        // Buscar APENAS na tabela ad_tarefa usando estagiario_responsavel_id
        $qb = $this->tarefaRepository->createQueryBuilder('t');
        $qb->select('t', 'u')
           ->join('t.especieTarefa', 'et')
           ->join('et.generoTarefa', 'gt')
           ->join('t.estagiarioResponsavel', 'u')
           ->where('u.id IN (:usuarioIds)')
           ->andWhere('gt.id = :generoId')
           ->andWhere('t.estagiarioResponsavel IS NOT NULL')
           ->andWhere('t.distribuicaoEstagiarioAutomatica = true') // Filtro adicionado: só considera distribuições automáticas
           ->andWhere('t.dataHoraDistribuicao IS NOT NULL')
           ->orderBy('t.dataHoraDistribuicao', 'DESC')
           ->addOrderBy('t.id', 'DESC') // Usar ID como critério de desempate
           ->setMaxResults(1)
           ->setParameter('usuarioIds', $usuarioIds)
           ->setParameter('generoId', $generoId);

        $result = $qb->getQuery()->getResult();
        
        if (!empty($result)) {
            $tarefa = $result[0];
            $estagiario = $tarefa->getEstagiarioResponsavel();
            $dataDistribuicao = $tarefa->getDataHoraDistribuicao();
            
            error_log("✅ ÚLTIMO estagiário encontrado: " . $estagiario->getNome() . 
                     " (ID: " . $estagiario->getId() . ") - Tarefa " . $tarefa->getId() . 
                     " em " . $dataDistribuicao->format('Y-m-d H:i:s'));
            
            return [
                'usuario_id' => $estagiario->getId(),
                'nome' => $estagiario->getNome(),
                'data' => $dataDistribuicao,
                'tarefa_id' => $tarefa->getId()
            ];
        }
        
        error_log("⚠️ Nenhum estagiário com tarefa anterior encontrado para este gênero");
        return null;
    }
    
    /**
     * Reorganiza a fila para começar após o último que recebeu
     */
    private function reorganizarFilaAposUltimo(array $estagiarios, int $ultimoUsuarioId): array
    {
        // Encontrar posição do último que recebeu
        $posicaoUltimo = -1;
        foreach ($estagiarios as $index => $colaborador) {
            if ($colaborador->getUsuario()->getId() === $ultimoUsuarioId) {
                $posicaoUltimo = $index;
                break;
            }
        }
        
        if ($posicaoUltimo === -1) {
            error_log("⚠️ Último estagiário não encontrado na lista atual, usando ordem original");
            return $estagiarios;
        }
        
        // Reorganizar: tudo após o último + tudo antes do último + o último no final
        $filaReorganizada = [];
        
        // Adicionar os que vêm depois do último
        for ($i = $posicaoUltimo + 1; $i < count($estagiarios); $i++) {
            $filaReorganizada[] = $estagiarios[$i];
        }
        
        // Adicionar os que vêm antes do último
        for ($i = 0; $i < $posicaoUltimo; $i++) {
            $filaReorganizada[] = $estagiarios[$i];
        }
        
        // Adicionar o último no final
        $filaReorganizada[] = $estagiarios[$posicaoUltimo];
        
        error_log("🔄 Reorganização: " . $estagiarios[$posicaoUltimo]->getUsuario()->getNome() . " foi para o final");
        
        return $filaReorganizada;
    }

    /**
     * Busca tarefas pendentes para estagiários por gênero
     */
    private function findTarefasPendentesEstagiarios(int $generoId, int $setorId): array
    {
        $qb = $this->tarefaRepository->createQueryBuilder('t');
        $qb->join('t.especieTarefa', 'et')
           ->join('et.generoTarefa', 'gt')
           ->join('t.setorResponsavel', 's')
           ->where('gt.id = :generoId')
           ->andWhere('s.id = :setorId')
           ->andWhere('t.dataHoraConclusaoPrazo IS NULL')
           ->andWhere('t.redistribuida = false')
           ->andWhere('t.estagiarioResponsavel IS NULL')
           ->orderBy('t.criadoEm', 'ASC')
           ->setParameter('generoId', $generoId)
           ->setParameter('setorId', $setorId);

        return $qb->getQuery()->getResult();
    }

    /**
     * Conta tarefas ativas (não concluídas) de um estagiário para um gênero específico
     * NOTA: Método mantido para compatêbilidade, mas não usado no novo algoritmo circular
     */
    private function contarTarefasAtivasEstagiario(int $usuarioId, int $generoId): int
    {
        $qb = $this->tarefaRepository->createQueryBuilder('t');
        $qb->select('COUNT(t.id)')
           ->join('t.especieTarefa', 'et')
           ->join('et.generoTarefa', 'gt')
           ->join('t.estagiarioResponsavel', 'u')
           ->where('u.id = :usuarioId')
           ->andWhere('gt.id = :generoId')
           ->andWhere('t.dataHoraConclusaoPrazo IS NULL')
           ->andWhere('t.redistribuida = false')
           ->setParameter('usuarioId', $usuarioId)
           ->setParameter('generoId', $generoId);

        $resultado = $qb->getQuery()->getSingleScalarResult();
        
        return (int) $resultado;
    }
}