<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoDocumento/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0009.
 *
 * @descSwagger=Os documentos oriundos de integração não podem ser vinculados!
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0009 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumento::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getDocumento()->getOrigemDados() || $restDto->getDocumentoVinculado()->getOrigemDados()) {
            $this->rulesTranslate->throwException('vinculacaoDocumento', '0009');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 9;
    }
}
