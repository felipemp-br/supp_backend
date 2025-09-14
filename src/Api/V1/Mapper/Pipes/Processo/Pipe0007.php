<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0007.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0007 implements PipeInterface
{
    private TramitacaoResource $tramitacaoResource;

    /**
     * Pipe0007 constructor.
     */
    public function __construct(
        TramitacaoResource $tramitacaoResource,
    ) {
        $this->tramitacaoResource = $tramitacaoResource;
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
        $tramitacaoExternaPendente = $entity->getTramitacoes()->filter(
            fn (Tramitacao $tramitacao) => !($tramitacao->getDataHoraRecebimento()) && ($tramitacao->getPessoaDestino())
        );
        if($tramitacaoExternaPendente->count() > 0){
            $restDto->setEmTramitacaoExterna(true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
