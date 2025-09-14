<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFaseResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Os processos são criados com a modalidade fase CORRENTE!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ModalidadeFaseResource $modalidadeFaseResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ModalidadeFaseResource $modalidadeFaseResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->modalidadeFaseResource = $modalidadeFaseResource;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeCreate',
            ],
        ];
    }

    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getModalidadeFase()) {
            $modalidadeFaseRepository = $this->modalidadeFaseResource->getRepository();
            $modalidadeFase = $modalidadeFaseRepository
                ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_1')]);
            $restDto->setModalidadeFase($modalidadeFase);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
