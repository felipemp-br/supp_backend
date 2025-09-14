<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Transicao/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Transicao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFaseResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Registra um historico pela transição do Processo!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        private HistoricoResource $historicoResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        //CRIA HISTÓRICO DA TRANSIÇÃO ARQUIVÍSTICA
        $historicoDto = new HistoricoDTO();
        $historicoDto->setProcesso($entity->getProcesso());
        $historicoDto->setDescricao(
            $restDto->getModalidadeTransicao()->getValor().' DE PROCESSO'
        );
        $this->historicoResource->create($historicoDto, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
