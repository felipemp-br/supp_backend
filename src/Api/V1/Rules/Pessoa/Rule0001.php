<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Pessoa/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Pessoa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=A instituição não pode ser cadastrada em duplicidade!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ParameterBagInterface $parameterBag;

    /**
     * Rule0001 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                ParameterBagInterface $parameterBag)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            Pessoa::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Pessoa|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Pessoa|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->parameterBag->get('supp_core.administrativo_backend.nome_instituicao') === $restDto->getNome()) {
            $this->rulesTranslate->throwException('pessoa', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
