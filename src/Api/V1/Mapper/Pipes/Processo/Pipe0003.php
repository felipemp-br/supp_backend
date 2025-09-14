<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;

/**
 * Class Pipe0003.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
{
    private NUPProviderManager $nupProviderManager;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        NUPProviderManager $nupProviderManager
    ) {
        $this->nupProviderManager = $nupProviderManager;
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param Processo|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $restDto->setNUPFormatado($this->nupProviderManager->getNupProvider($entity)
            ->formatarNumeroUnicoProtocolo($entity->getNUP()));
    }

    public function getOrder(): int
    {
        return 1;
    }
}
