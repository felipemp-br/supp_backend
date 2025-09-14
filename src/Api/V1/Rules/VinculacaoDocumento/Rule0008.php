<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoDocumento/Rule0008.php.
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
 * Class Rule0008.
 *
 * @descSwagger=Um documento não pode ser vinculado a ele mesmo!
 * @classeSwagger=Rule0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0008 constructor.
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
        // se o documento e o documento vinculado estiverem sendo criados ao mesmo tempo
        if (null === $restDto->getDocumento()->getId() &&
            null === $restDto->getDocumentoVinculado()->getId()) {
            return true;
        }

        if ($restDto->getDocumento()->getId() === $restDto->getDocumentoVinculado()->getId()) {
            $this->rulesTranslate->throwException('vinculacaoDocumento', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 8;
    }
}
