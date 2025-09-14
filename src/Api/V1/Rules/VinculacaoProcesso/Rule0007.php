<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0007.php.
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
 * Class Rule0007.
 *
 * @descSwagger=Esse NUP foi recebido via barramento/integração e não pode ser vinculado!
 * @classeSwagger=Rule0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    /**
     * Rule0007 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoProcesso::class => [
                'beforeCreate',
                'skipWhenCommand',
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
        if ($restDto->getProcessoVinculado()->getOrigemDados() &&
            'BARRAMENTO_PEN' === $restDto->getProcessoVinculado()->getOrigemDados()->getFonteDados() &&
                $restDto->getModalidadeVinculacaoProcesso()->getValor() !==
                $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_1')) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}
