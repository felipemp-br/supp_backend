<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ObjetoAvaliado/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ObjetoAvaliado;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ObjetoAvaliado as ObjetoAvaliadoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado as ObjetoAvaliadoEntity;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Consulta ou cria objeto avaliado.
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    public function __construct(
        private AvaliacaoManager $avaliacaoManager
    ) {
    }

    public function supports(): array
    {
        return [
            ObjetoAvaliadoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param ObjetoAvaliadoDTO|RestDtoInterface|null $restDto
     * @param ObjetoAvaliadoEntity|EntityInterface    $entity
     * @param string                                  $transactionId
     */
    public function execute(
        ObjetoAvaliadoDTO|RestDtoInterface|null $restDto,
        ObjetoAvaliadoEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        // verificar se existe um objeto avaliado e se nao existe

        $motorAvaliacao = $this
                ->avaliacaoManager
                ->getAvaliacao($restDto->getClasse(), []);

        // define o valor inicial da avaliacao
        $restDto->setAvaliacaoResultante(
                    $motorAvaliacao->getValorInicial($restDto, [])
            );
    }

    public function getOrder(): int
    {
        return 1;
    }
}
