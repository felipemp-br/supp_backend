<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Ajusta o setor e o usuário responsáveis de acordo com a tarefa ou o processo origem!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getProcesso()) {
            $restDto->getSetorOrigem() ?? $restDto->setSetorOrigem($restDto->getProcesso()->getSetorAtual());
            $restDto->setSetorResponsavel($restDto->getProcesso()->getSetorAtual());
            $restDto->setUsuarioResponsavel($this->tokenStorage->getToken()->getUser());
        }

        if ($restDto->getTarefaOrigem()) {
            $restDto->getSetorOrigem() ?? $restDto->setSetorOrigem($restDto->getTarefaOrigem()->getSetorResponsavel());
            $restDto->setSetorResponsavel($restDto->getTarefaOrigem()->getSetorResponsavel());
            $restDto->setUsuarioResponsavel($restDto->getTarefaOrigem()->getUsuarioResponsavel());
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
