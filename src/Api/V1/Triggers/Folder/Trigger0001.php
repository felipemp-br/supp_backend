<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Folder/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Folder;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Folder;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFolderResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Folder as FolderEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Caso a modalidade não seja informado, assume o valor de tarefa!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private ModalidadeFolderResource $modalidadeFolderResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ModalidadeFolderResource $modalidadeFolderResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->modalidadeFolderResource = $modalidadeFolderResource;
    }

    public function supports(): array
    {
        return [
            Folder::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Folder|RestDtoInterface|null $restDto
     * @param FolderEntity|EntityInterface $entity
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getModalidadeFolder()) {
            $restDto->setModalidadeFolder(
                $this->modalidadeFolderResource->getRepository()->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_folder.const_1')]
                )
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
