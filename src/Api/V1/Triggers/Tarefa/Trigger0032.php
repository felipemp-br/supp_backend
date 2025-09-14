<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0032.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;

/**
 * Class Trigger0032.
 *
 * @descSwagger=Gerencia a flag distribuicaoEstagiarioAutomatica: marca como false quando estagiário é definido/trocado manualmente, e como true quando é removido!
 * @classeSwagger=Trigger0032
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0032 implements TriggerInterface
{
    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     * @param string                       $transactionId
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        error_log("=== TRIGGER0032 EXECUTADO ===");
        
        // Verifica se o campo estagiarioResponsavel foi modificado
        $estagiarioAtual = $entity->getEstagiarioResponsavel();
        $novoEstagiario = $restDto->getEstagiarioResponsavel();
        
        error_log("Estagiário atual: " . ($estagiarioAtual ? $estagiarioAtual->getId() . " - " . $estagiarioAtual->getNome() : "NULL"));
        error_log("Novo estagiário: " . ($novoEstagiario ? (is_object($novoEstagiario) ? $novoEstagiario->getId() . " - " . $novoEstagiario->getNome() : $novoEstagiario) : "NULL"));
        
        // Se houve alteração no estagiário responsável (incluindo definir ou remover)
        $estagiarioFoiAlterado = false;
        
        if ($estagiarioAtual === null && $novoEstagiario !== null) {
            // Estagiário foi definido
            $estagiarioFoiAlterado = true;
            error_log("Caso 1: Estagiário foi definido");
        } elseif ($estagiarioAtual !== null && $novoEstagiario === null) {
            // Estagiário foi removido
            $estagiarioFoiAlterado = true;
            error_log("Caso 2: Estagiário foi removido");
        } elseif ($estagiarioAtual !== null && $novoEstagiario !== null) {
            // Estagiário foi trocado - comparar IDs
            $idAtual = $estagiarioAtual->getId();
            $idNovo = is_object($novoEstagiario) ? $novoEstagiario->getId() : $novoEstagiario;
            $estagiarioFoiAlterado = ($idAtual !== $idNovo);
            error_log("Caso 3: Estagiário foi trocado - ID atual: $idAtual, ID novo: $idNovo, Alterado: " . ($estagiarioFoiAlterado ? "SIM" : "NÃO"));
        }
        
        // Tratamento especial para quando o estagiário é removido
        if ($estagiarioAtual !== null && $novoEstagiario === null) {
            // Quando REMOVE o estagiário, a tarefa deve voltar para a distribuição automática
            error_log("Estagiário foi REMOVIDO - Marcando distribuicaoEstagiarioAutomatica = true");
            $restDto->setDistribuicaoEstagiarioAutomatica(true);
        } elseif ($estagiarioFoiAlterado) {
            // Quando DEFINE ou TROCA estagiário manualmente, marca como distribuição não automática
            error_log("Estagiário foi DEFINIDO/TROCADO - Marcando distribuicaoEstagiarioAutomatica = false");
            $restDto->setDistribuicaoEstagiarioAutomatica(false);
        } else {
            error_log("Nenhuma alteração detectada no estagiário");
        }
        
        error_log("=== FIM TRIGGER0032 ===");
    }

    public function getOrder(): int
    {
        return 2; // Executa após outros triggers básicos
    }
}