<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Cronjob/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Cronjob;

use DateTime;
use SuppCore\AdministrativoBackend\Entity\Cronjob;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as CronjobDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Atualiza informações de ultima execução do job.
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     */
    public function __construct(private TokenStorageInterface $tokenStorage) {
    }

    public function supports(): array
    {
        return [
            CronjobDTO::class => [
                'beforeStartJob'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return void
     */
    public function execute(
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $usuario = $dto->getAtualizadoPor() ?? $dto->getUsuarioUltimaExecucao() ?? $dto->getCriadoPor();
        if ($this->tokenStorage->getToken()?->getUser()?->getId()) {
            $usuario = $this->tokenStorage->getToken()->getUser();
        }

        $dto->setUsuarioUltimaExecucao($usuario);
        $dto->setDataHoraUltimaExecucao(new DateTime());
        $dto->setStatusUltimaExecucao(Cronjob::ST_EXECUCAO_EM_EXECUCAO);
        $dto->setPercentualExecucao(0);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
