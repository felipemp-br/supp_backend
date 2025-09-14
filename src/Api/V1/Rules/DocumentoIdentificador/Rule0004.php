<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DocumentoIdentificador/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoIdentificador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIdentificador;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=As pessoas validadas pela instituição não podem ser alteradas pelos Usuários!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            DocumentoIdentificador::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param DocumentoIdentificador|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getPessoa()->getPessoaValidada()) {
            $this->rulesTranslate->throwException('documentoIdentificador', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
