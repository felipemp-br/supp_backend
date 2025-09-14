<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital\Message\VerificacaoVirusMessage;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0015.
 *
 * @descSwagger=Faz o push da mensagem de verificação de virus do componente digital
 * @classeSwagger=Trigger0015
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0015 implements TriggerInterface
{
    /**
     * Trigger0015 constructor.
     */
    public function __construct(private ComponenteDigitalResource $componenteDigitalResource,
                                private TransactionManager $transactionManager,
                                private ParameterBagInterface $parameterBag)
    {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     * @param string $transactionId
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigitalEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        if ($this->parameterBag->get('virus_verification_enabled')) {
            $restDto->setStatusVerificacaoVirus(ComponenteDigitalEntity::SVV_PENDENTE);
            $message = new VerificacaoVirusMessage($entity->getUuid());
            $this->transactionManager->addAsyncDispatch($message, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
