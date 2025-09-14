<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0031.
 *
 * @descSwagger=Dispora o processo de triagem de documento pela IA, se ela estiver disponivel no sistema,
 * a partir do conteúdo do componente digital.
 *
 * @classeSwagger=Trigger0031
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0031 implements TriggerInterface
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
            ComponenteDigitalDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface    $entity
     * @param string                                     $transactionId
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigitalEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        if (!mb_strlen($restDto->getConteudo())) {
            return;
        } else {
            if ($this->configModuloTriagemHelper->executaTriagemJuntada()) {
                $this->configModuloTriagemHelper->checkAndDispatchTriagemMessage(
                    $restDto->getDocumento(),
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 30;
    }
}
