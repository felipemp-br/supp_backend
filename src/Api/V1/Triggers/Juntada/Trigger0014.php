<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao\Helpers\ConfigModuloClassificaTipoDocumentoHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0014.
 *
 * @descSwagger=Dispora o processo de classificação de documento pela IA.
 *
 * @classeSwagger=Trigger0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0014 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param ConfigModuloClassificaTipoDocumentoHelper $configModuloClassificaTipoDocumentoHelper
     */
    public function __construct(
        private readonly ConfigModuloClassificaTipoDocumentoHelper $configModuloClassificaTipoDocumentoHelper
    ) {
    }

    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param JuntadaDTO|RestDtoInterface|null $restDto
     * @param JuntadaEntity|EntityInterface    $entity
     * @param string                                     $transactionId
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        JuntadaDTO|RestDtoInterface|null $restDto,
        JuntadaEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        $this->configModuloClassificaTipoDocumentoHelper->checkAndDispatchClassificaDocumentoMessage(
            $restDto->getDocumento(),
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 30;
    }
}
