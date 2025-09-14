<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Setor/Rule0001.php.
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
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Setores das espécies Protocolo e Arquivo e não podem ser criados pelos usuários!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private TransactionManager $transactionManager;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->transactionManager = $transactionManager;
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
        if (!$restDto->getParent()) { // unidade
            return true;
        }

        if ($this->transactionManager->getContext('criacaoUnidade', $transactionId)) {
            return true;
        }

        if (($this->parameterBag->get('constantes.entidades.especie_setor.const_1') === $restDto->getEspecieSetor()->getNome() ||
            $this->parameterBag->get('constantes.entidades.especie_setor.const_2') === $restDto->getEspecieSetor()->getNome())) {
            $this->rulesTranslate->throwException('setor', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
