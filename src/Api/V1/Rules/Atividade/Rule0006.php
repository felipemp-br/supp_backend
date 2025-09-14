<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Atividade/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006.
 *
 * @descSwagger=Não é possível remover atividade quando tarefa está encerrada!
 *
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    public function __construct(private readonly RulesTranslate $rulesTranslate)
    {
    }

    public function supports(): array
    {
        return [
            AtividadeEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param AtividadeDTO|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface    $entity
     * @param string                             $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        AtividadeDTO|RestDtoInterface|null $restDto,
        AtividadeEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($entity->getTarefa()->getDataHoraConclusaoPrazo()) {
            $this->rulesTranslate->throwException('atividade', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}
