<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoEtiqueta/Trigger0006.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoEtiqueta;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Seta o usuário e data hora da aprovação da sugestão da ação da etiqueta.
 * @classeSwagger= Trigger0006
 */
class Trigger0006 implements TriggerInterface
{
    /**
     * Trigger0006 constructor.
     */
    public function __construct(private TokenStorageInterface $tokenStorage) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'beforeAprovarSugestao',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDTO|RestDtoInterface|null $vinculacaoEtiquetaDTO
     * @param VinculacaoEtiquetaEntity|EntityInterface    $vinculacaoEtiquetaEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $vinculacaoEtiquetaDTO,
        EntityInterface $vinculacaoEtiquetaEntity,
        string $transactionId
    ): void {
        $vinculacaoEtiquetaDTO
            ->setDataHoraAprovacaoSugestao(new DateTime())
            ->setUsuarioAprovacaoSugestao($this->tokenStorage->getToken()->getUser());
    }

    public function getOrder(): int
    {
        return 1;
    }
}
