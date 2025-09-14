<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0008.
 *
 * @descSwagger=O NUP é eletrônico e não pode receber juntadas de documentos sem componentes digitais!
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
    public function __construct(RulesTranslate $rulesTranslate,
                                private ParameterBagInterface $parameterBag)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Juntada|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getDocumento()->getId() && (0 === count($restDto->getDocumento()->getComponentesDigitais())) &&
            $this->parameterBag->get('constantes.entidades.modalidade_meio.const_1') === $restDto->getVolume()->getProcesso()->getModalidadeMeio()->getValor()) {
            $this->rulesTranslate->throwException('juntada', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 8;
    }
}
