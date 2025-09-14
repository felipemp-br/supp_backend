<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Assunto/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assunto;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Seta os favoritos do assunto criado!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    /**
     * Trigger0003 constructor.
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
            Assunto::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Assunto|RestDtoInterface|null $restDto
     * @param AssuntoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $blocoFavoritos = [];

        /*
         * Assunto Administrativo.
         */
        $blocoFavoritos[] = [
            'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
            'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getAssuntoAdministrativo())),
            'objectId' => $restDto->getAssuntoAdministrativo()->getId(),
            'context' => 'assunto_'.
                $restDto->getProcesso()->getEspecieProcesso()->getId().
                '_assunto_administrativo',
        ];

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos)), $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
