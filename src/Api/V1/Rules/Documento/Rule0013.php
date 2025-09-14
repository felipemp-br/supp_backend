<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0013.
 *
 * @descSwagger=Tarefa de origem não informada!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{

    /**
     * Rule0013 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate
    )
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'afterConverteAnexoEmMinuta',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getTarefaOrigem()) {
            $this->rulesTranslate->throwException('documento', '0013');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
