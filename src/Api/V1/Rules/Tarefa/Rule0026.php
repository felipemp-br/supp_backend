<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Tarefa/Rule0026.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0026.
 *
 * @descSwagger=Não é possível editar enquanto a tarefa está em encerramento assíncrono!
 *
 * @classeSwagger=Rule0026
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0026 implements RuleInterface
{
    public function __construct(private readonly RulesTranslate $rulesTranslate)
    {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeUpdate',
                'beforePatch',
                'beforeCiencia',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface    $entity
     * @param string                          $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        TarefaDTO|RestDtoInterface|null $restDto,
        TarefaEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        $agora = new DateTime();

        if ($entity->getDataHoraAsyncLock()
            && ($entity->getDataHoraAsyncLock() > $agora)
        ) {
            $this->rulesTranslate->throwException('tarefa', '0026');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 26;
    }
}
