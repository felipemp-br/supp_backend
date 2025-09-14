<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Lotacao/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Lotacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CoordenadorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger  =Se uma lotação for definida como principal, todos as demais lotações deverão ser alteradas para não principais!
 * @classeSwagger=Trigger0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
//    private LotacaoResource $lotacaoResource;

    private CoordenadorResource $coordenadorResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        CoordenadorResource $coordenadorResource
    ) {
        $this->coordenadorResource = $coordenadorResource;
    }

    public function supports(): array
    {
        return [
            Lotacao::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getCriarCoordenacao()) {
            $dtoCoordenador = new Coordenador();
            $dtoCoordenador->setSetor($restDto->getSetor());
            $dtoCoordenador->setUsuario($restDto->getColaborador()->getUsuario());

            $this->coordenadorResource->create($dtoCoordenador, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 3;
    }
}
