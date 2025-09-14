<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Cronjob/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Cronjob;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as CronjobDTO;
use SuppCore\AdministrativoBackend\Cronjob\CronjobExpressionServiceInterface;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Cronjob;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{

    public function __construct(private CronjobExpressionServiceInterface $cronjobExpressionService)
    {
    }

    public function supports(): array
    {
        return [
            CronjobDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ($restDto->getAtivo()) {
            $restDto->setDataHoraProximaExecucao($this->cronjobExpressionService->nextRunDate(
                $entity->getPeriodicidade(),
                $entity->getDataHoraUltimaExecucao()
            ));
        }

        if ($restDto->getStatusUltimaExecucao() !== null) {
            switch ($restDto->getStatusUltimaExecucao()) {
                case Cronjob::ST_EXECUCAO_SUCESSO:
                    $restDto->setTextoStatusUltimaExecucao('Sucesso');
                    break;
                case Cronjob::ST_EXECUCAO_EM_EXECUCAO:
                    $restDto->setTextoStatusUltimaExecucao('Em execução');
                    break;
                case Cronjob::ST_EXECUCAO_ERRO:
                    $restDto->setTextoStatusUltimaExecucao('Erro');
                    break;
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
