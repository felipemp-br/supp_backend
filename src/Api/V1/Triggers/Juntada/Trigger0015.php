<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0015.
 *
 * @descSwagger=Dispora o processo de triagem de documento pela IA.
 *
 * @classeSwagger=Trigger0015
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0015 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param ConfigModuloTriagemHelper $configModuloTriagemHelper
     */
    public function __construct(
        private readonly ConfigModuloTriagemHelper $configModuloTriagemHelper
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
        if ($this->configModuloTriagemHelper->executaTriagemJuntada()) {
            $this->configModuloTriagemHelper->checkAndDispatchTriagemMessage(
                $restDto->getDocumento(),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 30;
    }
}
