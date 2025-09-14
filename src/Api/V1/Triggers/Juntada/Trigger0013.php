<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Juntada/Trigger0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0013.
 *
 * @descSwagger=Promove o recebimento da Remessa se o usuário externo for vinculado a Pessoa Destino
 * @classeSwagger=Trigger0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0013 implements TriggerInterface
{

    private TokenStorageInterface $tokenStorage;
    private TramitacaoResource $tramitacaoResource;


    /**
     * Trigger0013 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        TramitacaoResource $tramitacaoResource,
        private TransactionManager $transactionManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->tramitacaoResource = $tramitacaoResource;

    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($this->transactionManager->getContext('pessoaVinculadaConveniada', $transactionId)) {
            $tramitacaoPendente = $this->tramitacaoResource->getRepository()
                ->findTramitacaoPendentePorProcesso($restDto->getVolume()->getProcesso()->getId());
            //Seta recebimento da Remessa
            $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                $tramitacaoPendente->getId(),
                Tramitacao::class
            );
            $tramitacaoDto->setDataHoraRecebimento(new DateTime());
            $tramitacaoDto->setUsuarioRecebimento($this->tokenStorage->getToken()->getUser());
            $this->tramitacaoResource->update(
                $tramitacaoPendente->getId(),
                $tramitacaoDto,
                $transactionId
            );
            $this->transactionManager->removeContext('pessoaVinculadaConveniada', $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
