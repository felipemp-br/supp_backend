<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Etiqueta/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Etiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Caso não seja informado, assume que a modalidade da etiqueta é tarefa!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ModalidadeEtiquetaResource $modalidadeEtiquetaResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ModalidadeEtiquetaResource $modalidadeEtiquetaResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->modalidadeEtiquetaResource = $modalidadeEtiquetaResource;
    }

    public function supports(): array
    {
        return [
            Etiqueta::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Etiqueta|RestDtoInterface|null $restDto
     * @param EtiquetaEntity|EntityInterface $entity
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getModalidadeEtiqueta()) {
            $restDto->setModalidadeEtiqueta(
                $this->modalidadeEtiquetaResource->getRepository()->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_etiqueta.const_1')]
                )
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
