<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0016.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0016.
 *
 * @descSwagger=Seta os favoritos do processo criado!
 * @classeSwagger=Trigger0016
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0016 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    /**
     * Trigger0016 constructor.
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
            Processo::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $blocoFavoritos = [];

        /*
         * Espécie Processo.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getEspecieProcesso())),
                    'objectId' => $restDto->getEspecieProcesso()->getId(),
                    'context' => 'processo_'.
                        strtolower($restDto->getEspecieProcesso()->getGeneroProcesso()->getNome()).
                        '_especie_processo',
                ];

        /*
         * Modalidade Meio.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getModalidadeMeio())),
                    'objectId' => $restDto->getModalidadeMeio()->getId(),
                    'context' => 'processo_'.
                        $restDto->getEspecieProcesso()->getId().
                        '_modalidade_meio',
                ];

        /*
         * Classificacao.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getClassificacao())),
                    'objectId' => $restDto->getClassificacao()->getId(),
                    'context' => 'processo_'.
                        $restDto->getEspecieProcesso()->getId().
                        '_classificacao',
                ];

        /*
         * Procedencia.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getProcedencia())),
                    'objectId' => $restDto->getProcedencia()->getId(),
                    'context' => 'processo_'.
                        strtolower($restDto->getEspecieProcesso()->getGeneroProcesso()->getNome()).
                        '_procedencia',
                ];

        /*
         * Setor Atual.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectId' => $restDto->getSetorAtual()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorAtual())),
                    'context' => 'processo_'.
                        $restDto->getEspecieProcesso()->getId().
                        '_setor_atual',
                ];

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos)), $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
