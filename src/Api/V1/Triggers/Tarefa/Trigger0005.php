<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use JsonException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use function get_class;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Seta os favoritos da tarefa criada!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TransactionManager $transactionManager
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TarefaDTO|null $restDto
     * @param EntityInterface|TarefaEntity $entity
     * @param string $transactionId
     *
     * @return void
     *
     * @throws JsonException
     */
    public function execute(
        RestDtoInterface|TarefaDTO|null $restDto,
        EntityInterface|TarefaEntity $entity,
        string $transactionId
    ): void {
        if (null === $restDto->getEspecieTarefa()?->getId()
            || null === $restDto->getSetorResponsavel()?->getUnidade()->getId()
            || null === $restDto->getProcesso()?->getEspecieProcesso()->getId()
            || null === $this->tokenStorage->getToken()?->getUser()?->getId()
        ) {
            return;
        }

        $blocoFavoritos = [];

        $blocoFavoritos[] = [
            'usuario' => $this->tokenStorage->getToken()?->getUser()->getId(),
            'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getEspecieTarefa())),
            'objectId' => $restDto->getEspecieTarefa()->getId(),
            'context' => 'tarefa_'.
                $restDto->getProcesso()->getEspecieProcesso()->getId().
                '_especie_tarefa',
        ];

        $blocoFavoritos[] = [
            'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
            'objectId' => $restDto->getSetorResponsavel()->getUnidade()->getId(),
            'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorResponsavel()->getUnidade())),
            'context' => 'tarefa_'.
                $restDto->getProcesso()->getEspecieProcesso()->getId().
                '_unidade_responsavel',
        ];

        $blocoFavoritos[] = [
            'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
            'objectId' => $restDto->getSetorResponsavel()->getId(),
            'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorResponsavel())),
            'context' => 'tarefa_'.
                $restDto->getProcesso()->getEspecieProcesso()->getId().
                '_setor_responsavel_unidade_'.$restDto->getSetorResponsavel()->getUnidade()->getId(),
        ];

        $blocoFavoritos[] = [
            'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
            'objectId' => $restDto->getUsuarioResponsavel()->getId(),
            'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getUsuarioResponsavel())),
            'context' => 'tarefa_'.
                $restDto->getProcesso()->getEspecieProcesso()->getId().
                '_usuario_responsavel_setor_'.$restDto->getSetorResponsavel()->getId(),
        ];

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos, JSON_THROW_ON_ERROR)), $transactionId);
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 5;
    }
}
