<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\AreaTrabalho;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AreaTrabalhoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Caso seja o documento seja uma minuta, será criada uma área de trabalho para o usuário logado!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private AreaTrabalhoResource $areaTrabalhoResource;

    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        AreaTrabalhoResource $areaTrabalhoResource,
        TokenStorageInterface $tokenStorage
    ) {
        $this->areaTrabalhoResource = $areaTrabalhoResource;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var \SuppCore\AdministrativoBackend\Entity\Documento $documento */
        $documento = $entity;

        if ($this->tokenStorage->getToken() && (0 === $documento->getJuntadas()->count())) {
            $areaTrabalhoDTO = new AreaTrabalho();
            $areaTrabalhoDTO->setDocumento($documento);
            $areaTrabalhoDTO->setUsuario($this->tokenStorage->getToken()->getUser());
            $areaTrabalhoDTO->setDono(true);

            // $this->areaTrabalhoResource->create($areaTrabalhoDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
