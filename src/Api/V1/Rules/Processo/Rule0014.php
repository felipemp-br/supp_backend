<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

use function in_array;

/**
 * Class Rule0014.
 *
 * @descSwagger=Não é permitido alterar o campo outroNumero para processos do Barramento
 * @classeSwagger=Rule0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0014 implements RuleInterface
{
    /**
     * Rule0014 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        if ($entity->getOrigemDados() &&
            'BARRAMENTO_PEN' === $entity->getOrigemDados()->getFonteDados()
            && in_array('outroNumero', $restDto->getVisited(), true)
            && $restDto->getOutroNumero()
            && ($restDto->getOutroNumero() !== $entity->getOutroNumero())
        ) {
            $this->rulesTranslate->throwException('processo', '0016');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 14;
    }
}
