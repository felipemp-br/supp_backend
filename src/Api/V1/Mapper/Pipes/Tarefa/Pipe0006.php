<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Pipes/Tarefa/Pipe0006.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0006.
 * 
 * Pipe para popular tarefas relacionadas no DTO
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0006 implements PipeInterface
{
    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($restDto instanceof TarefaDTO && $entity instanceof TarefaEntity) {
            $tarefasRelacionadas = [];
            
            foreach ($entity->getTarefasRelacionadas() as $tarefaRelacionada) {
                $tarefasRelacionadas[] = [
                    'id' => $tarefaRelacionada->getId(),
                    'especieTarefa' => $tarefaRelacionada->getEspecieTarefa() ? [
                        'id' => $tarefaRelacionada->getEspecieTarefa()->getId(),
                        'nome' => $tarefaRelacionada->getEspecieTarefa()->getNome()
                    ] : null,
                    'setorResponsavel' => $tarefaRelacionada->getSetorResponsavel() ? [
                        'id' => $tarefaRelacionada->getSetorResponsavel()->getId(),
                        'sigla' => $tarefaRelacionada->getSetorResponsavel()->getSigla(),
                        'nome' => $tarefaRelacionada->getSetorResponsavel()->getNome()
                    ] : null,
                    'concluida' => (bool) $tarefaRelacionada->getDataHoraConclusaoPrazo()
                ];
            }
            
            $restDto->setTarefasRelacionadas($tarefasRelacionadas);
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 6;
    }
}