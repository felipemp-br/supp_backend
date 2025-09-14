<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao\Helpers\ConfigModuloClassificaTipoDocumentoHelper;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0030.
 *
 * @descSwagger=Dispora o processo de classificação de documento pela IA, se ela estiver disponivel no sistema,
 * a partir do conteúdo do componente digital
 *
 * @classeSwagger=Trigger0030
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0030 implements TriggerInterface
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
        // verifica se ha conteudo, se nao houver, retorna
        if (strlen($restDto->getConteudo()) <= 0) {
            return;
        } else {
            $this->configModuloClassificaTipoDocumentoHelper->checkAndDispatchClassificaDocumentoMessage(
                $restDto,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 30;
    }
}
