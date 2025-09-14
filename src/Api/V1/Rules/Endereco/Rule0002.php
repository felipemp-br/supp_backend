<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Endereco/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Endereco;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=O Endereco está vinculado a outro! Exclua primeiro a vinculação!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0002 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Endereco::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco|RestDtoInterface|null $restDto
     * @param Endereco|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getPessoa()->getPessoaValidada()) {
            $this->rulesTranslate->throwException('endereco', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
