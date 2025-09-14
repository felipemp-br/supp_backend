<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Atividade/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Se assim requerido, marca o encerramento da tarefa!
 *
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        private readonly TarefaResource $tarefaResource,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TransicaoWorkflowRepository $transicaoWorkflowRepository
    ) {
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getEncerraTarefa()) {
            // ajustes necessários ao módulo jud
            $usuario = $this->tokenStorage->getToken()?->getUser();
            if (!$usuario) {
                $usuario = $restDto?->getUsuario();
            }

            $tarefaDTO = new Tarefa();
            $tarefaDTO->setDataHoraConclusaoPrazo(new DateTime());
            $tarefaDTO->setUsuarioConclusaoPrazo($usuario);
            $tarefaDTO->setDataHoraAsyncLock(null);
            $tarefaDTO->setFolder(null);

            $tarefaEntity = $this->tarefaResource->update(
                $restDto->getTarefa()->getId(),
                $tarefaDTO,
                $transactionId,
                true
            );

            if ($tarefaEntity->getVinculacaoWorkflow()) {
                $transicaoAtual = $this->transicaoWorkflowRepository
                    ->findOneBy(
                        [
                            'workflow' => $tarefaEntity->getVinculacaoWorkflow()->getWorkflow(),
                            'especieAtividade' => $restDto->getEspecieAtividade(),
                            'especieTarefaFrom' => $tarefaEntity->getEspecieTarefa(),
                        ]
                    );

                if ($transicaoAtual) {
                    // Cria próxima tarefa workflow
                    $inicioPrazo = new DateTime();
                    $finalPrazo = new DateTime();
                    $diasPrazo = 'P'.($transicaoAtual->getQtdDiasPrazo() ?: 5).'D';
                    $finalPrazo->add(new DateInterval($diasPrazo));
                    $proximaTarefaWorkflowDTO = (new Tarefa())
                        ->setProcesso($tarefaEntity->getProcesso())
                        ->setEspecieTarefa($transicaoAtual->getEspecieTarefaTo())
                        ->setDistribuicaoAutomatica($restDto->getDistribuicaoAutomatica())
                        ->setSetorResponsavel($restDto->getSetorResponsavel())
                        ->setUsuarioResponsavel($restDto->getUsuarioResponsavel())
                        ->setVinculacaoWorkflow($tarefaEntity->getVinculacaoWorkflow())
                        ->setDataHoraInicioPrazo($inicioPrazo)
                        ->setDataHoraFinalPrazo($finalPrazo);

                    $this->tarefaResource->create(
                        $proximaTarefaWorkflowDTO,
                        $transactionId
                    );
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
