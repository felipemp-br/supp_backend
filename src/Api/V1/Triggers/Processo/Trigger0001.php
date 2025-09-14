<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger  =Controla a atribuição de NUP!
 * @classeSwagger=Trigger0001
 */
class Trigger0001 implements TriggerInterface
{
    private NUPProviderManager $nupProviderManager;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        NUPProviderManager $nupProviderManager
    ) {
        $this->nupProviderManager = $nupProviderManager;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeCreate',
                'beforeAutuar',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ((ProcessoEntity::UA_PROCESSO === $restDto->getUnidadeArquivistica() ||
                ProcessoEntity::UA_DOCUMENTO_AVULSO === $restDto->getUnidadeArquivistica()) &&
            (ProcessoEntity::TP_NOVO === $restDto->getTipoProtocolo())) {
            $restDto->setNUP(
                $this->nupProviderManager->getNupProvider($restDto)->gerarNumeroUnicoProtocolo($restDto)
            );
        }

        if (ProcessoEntity::UA_DOSSIE === $restDto->getUnidadeArquivistica()) {
            $restDto->setNUP($entity->getUuid());
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
