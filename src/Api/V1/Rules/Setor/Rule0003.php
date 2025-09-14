<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Setor/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Setor;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger  =Setores das espécies Protocolo e Arquivo e não podem ter setores filhos!
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
            Setor::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Setor|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getParent() || !$restDto->getParent()->getEspecieSetor()) { // unidade
            return true;
        }

        if ($this->parameterBag->get('constantes.entidades.especie_setor.const_1') === $restDto->getParent()->getEspecieSetor()->getNome() ||
            $this->parameterBag->get('constantes.entidades.especie_setor.const_2') === $restDto->getParent()->getEspecieSetor()->getNome()) {
            $this->rulesTranslate->throwException('setor', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
