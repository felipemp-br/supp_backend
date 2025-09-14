<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Transicao/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0005.
 *
 * @descSwagger=O NUP deve estar no setor de Arquivo para sofrer a transição arquivística!
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0005 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                private ParameterBagInterface $parameterBag)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Transicao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->parameterBag->get('constantes.entidades.especie_setor.const_2') !== $restDto->getProcesso()->getSetorAtual()->getEspecieSetor()->getNome() &&
            $this->parameterBag->get('constantes.entidades.modalidade_transicao.const_3') === $restDto->getModalidadeTransicao()->getValor()) {
            $this->rulesTranslate->throwException('transicao', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 8;
    }
}
