<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Transicao/Rule0006.php.
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
 * Class Rule0006.
 *
 * @descSwagger=O NUP está atualmente na Fase Corrente e não pode ser eliminado diretamente em razão de sua classificação!
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
        if ($this->parameterBag->get('constantes.entidades.modalidade_transicao.const_5') === $restDto->getModalidadeTransicao()->getValor() &&
            ($this->parameterBag->get('constantes.entidades.modalidade_fase.const_1') === $restDto->getProcesso()->getModalidadeFase()->getValor()) &&
            ($restDto->getProcesso()->getClassificacao()->getPrazoGuardaFaseIntermediariaAno() ||
                $restDto->getProcesso()->getClassificacao()->getPrazoGuardaFaseIntermediariaMes() ||
                $restDto->getProcesso()->getClassificacao()->getPrazoGuardaFaseIntermediariaDia() ||
                $restDto->getProcesso()->getClassificacao()->getPrazoGuardaFaseIntermediariaEvento())
        ) {
            $this->rulesTranslate->throwException('transicao', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 9;
    }
}
