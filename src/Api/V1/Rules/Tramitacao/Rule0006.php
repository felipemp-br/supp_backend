<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006.
 *
 * @descSwagger=Tramitação não pode ser excluída, pois já houve o recebimento anteriormente!
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0006 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao|RestDtoInterface|null $restDto
     * @param Tramitacao|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getDataHoraRecebimento()) {
            $this->rulesTranslate->throwException('tramitacao', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}
