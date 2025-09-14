<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0026.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Repository\ConfiguracaoNupRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0026.
 *
 * @descSwagger  =Caso processo não tenha ConfiguracaoNUP atribuido, seta a partir do Provider
 * @classeSwagger=Trigger0026
 */
class Trigger0026 implements TriggerInterface
{
    private NUPProviderManager $nupProviderManager;

    private ConfiguracaoNupRepository $configuracaoNupRepository;

    /**
     * Trigger0026 constructor.
     */
    public function __construct(
        NUPProviderManager $nupProviderManager,
        ConfiguracaoNupRepository $configuracaoNupRepository
    ) {
        $this->nupProviderManager = $nupProviderManager;
        $this->configuracaoNupRepository = $configuracaoNupRepository;
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
        if (!$restDto->getConfiguracaoNup()) {
            if ((ProcessoEntity::UA_PROCESSO === $restDto->getUnidadeArquivistica() ||
                ProcessoEntity::UA_DOCUMENTO_AVULSO === $restDto->getUnidadeArquivistica())) {
                $provider = $this->nupProviderManager->getNupProvider($restDto);
                $configuracaoNupEntity =
                    $this->configuracaoNupRepository->findOneBy(['sigla' => $provider->getSigla()]);
                $restDto->setConfiguracaoNup($configuracaoNupEntity);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
