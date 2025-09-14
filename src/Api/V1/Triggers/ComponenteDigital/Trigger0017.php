<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0017.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function hash;
use function mb_strtolower;
use function pathinfo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class Trigger0017.
 *
 * @descSwagger=Seta os favoritos do modelo criado!
 * @classeSwagger=Trigger0017
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0017 implements TriggerInterface
{

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var ComponenteDigital $restDto */
        if ($restDto->getModelo() && $restDto->getTarefaOrigem()) {
            $this->transactionManager
                ->addAsyncDispatch(new FavoritoMessage(json_encode(
                    [
                        [
                            'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                            'objectClass' => str_replace(
                                'Proxies\\__CG__\\',
                                '',
                                get_class($restDto->getModelo())
                            ),
                            'objectId' => $restDto->getModelo()->getId(),
                            'context' => 'modelo_'.
                                'genero_'.
                                mb_strtolower($restDto->getTarefaOrigem()
                                    ->getEspecieTarefa()
                                    ->getGeneroTarefa()
                                    ->getNome()),
                        ]
                    ]
                )), $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
