<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Processos/Documentos Avulsos deve ser do mesmo meio de suporte (físico x físico, eletrônico x eletrônico)!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0003 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                private ParameterBagInterface $parameterBag)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            VinculacaoProcesso::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoProcesso|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (($this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_3') == $restDto->getModalidadeVinculacaoProcesso()->getValor() ||
                $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_2') == $restDto->getModalidadeVinculacaoProcesso()->getValor()) &&
            $restDto->getProcesso()->getModalidadeMeio()->getValor() !== $restDto->getProcessoVinculado()->getModalidadeMeio()->getValor()) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
