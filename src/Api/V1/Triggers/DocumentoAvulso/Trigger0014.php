<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0014.
 *
 * @descSwagger=Seta os favoritos da documento_avulso criado!
 * @classeSwagger=Trigger0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0014 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    private TransactionManager $transactionManager;

    /**
     * Trigger0014 constructor.
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
            DocumentoAvulso::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $blocoFavoritos = [];

        /*
         * Espécie DocumentoAvulso.
         */
        $blocoFavoritos[] = [
                    'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                    'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getEspecieDocumentoAvulso())),
                    'objectId' => $restDto->getEspecieDocumentoAvulso()->getId(),
                    'context' => 'documento_avulso_'.
                        $restDto->getProcesso()->getEspecieProcesso()->getId().
                        '_especie_documento_avulso',
                ];

        if ($restDto->getSetorDestino()) {
            /*
             * Setor Destino.
             */
            $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getSetorDestino()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getSetorDestino())),
                'context' => 'documento_avulso_'.
                    $restDto->getProcesso()->getEspecieProcesso()->getId().
                    '_setor_destino',
            ];
        }

        if ($restDto->getPessoaDestino()) {
            /*
             * Pessoa Destino.
             */
            $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getPessoaDestino()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getPessoaDestino())),
                'context' => 'documento_avulso_'.
                    $restDto->getProcesso()->getEspecieProcesso()->getId().
                    '_pessoa_destino',
            ];
        }

        /*
         * Modelo.
         */
        $blocoFavoritos[] = [
                'usuario' => $this->tokenStorage->getToken()->getUser()->getId(),
                'objectId' => $restDto->getModelo()->getId(),
                'objectClass' => str_replace('Proxies\\__CG__\\', '', get_class($restDto->getModelo())),
                'context' => 'documento_avulso_'.
                    $restDto->getProcesso()->getEspecieProcesso()->getId().
                    '_modelo',
            ];

        $this->transactionManager
            ->addAsyncDispatch(new FavoritoMessage(json_encode($blocoFavoritos)), $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
