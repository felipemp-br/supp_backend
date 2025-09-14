<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Atividade/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Seta os favoritos atividade criada!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    /**
     * Trigger0006 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        TransactionManager $transactionManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Atividade|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $blocoFavoritos = [];

        /*
         * Espécie Atividade.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $restDto->getEspecieAtividade()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getEspecieAtividade())),
                    'context' => 'atividade_'.
                        $restDto->getTarefa()->getEspecieTarefa()->getId()
                        .'_especie_atividade',
                    ];

        if ('submeter_aprovacao' === $restDto->getDestinacaoMinutas()) {
            /*
             * Unidade Aprovação.
             */
            $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $restDto->getSetorAprovacao()->getUnidade()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorAprovacao()->getUnidade())),
                    'context' => 'atividade_'.
                        $restDto->getTarefa()->getEspecieTarefa()->getId()
                        .'_unidade_aprovacao',
                ];

            /*
             * Setor Aprovação.
             */
            $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $restDto->getSetorAprovacao()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorAprovacao())),
                    'context' => 'atividade_'.
                        $restDto->getTarefa()->getEspecieTarefa()->getId()
                        .'_setor_aprovacao_unidade_'.$restDto->getSetorAprovacao()->getUnidade()->getId(),
                ];

            /*
             * Usuário Aprovação.
             */
            $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $restDto->getUsuarioAprovacao()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getUsuarioAprovacao())),
                    'context' => 'atividade_'.
                        $restDto->getTarefa()->getEspecieTarefa()->getId()
                        .'_usuario_aprovacao_setor_'.$restDto->getSetorAprovacao()->getId(),
                ];
        }

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos)), $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
