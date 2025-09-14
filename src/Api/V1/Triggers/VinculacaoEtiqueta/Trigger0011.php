<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoEtiqueta/Trigger0011.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\AcoesEtiqueta\AcoesEtiquetaManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0011.
 *
 * @descSwagger=Verifica e executa as ações da etiqueta.
 *
 * @classeSwagger=Trigger0011
 */
class Trigger0011 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param AcoesEtiquetaManager $acoesEtiquetaManager
     */
    public function __construct(
        private readonly AcoesEtiquetaManager $acoesEtiquetaManager
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaInterface::class => [
                'afterCreate',
                'afterAprovarSugestao',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaInterface|RestDtoInterface|null $vinculacaoEtiquetaDTO
     * @param VinculacaoEtiquetaInterface|EntityInterface       $vinculacaoEtiquetaEntity
     * @param string                                            $transactionId
     *
     * @return void
     */
    public function execute(
        ?RestDtoInterface $vinculacaoEtiquetaDTO,
        EntityInterface $vinculacaoEtiquetaEntity,
        string $transactionId
    ): void {
        $this->acoesEtiquetaManager->handle(
            $vinculacaoEtiquetaEntity,
            $transactionId
        );
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
