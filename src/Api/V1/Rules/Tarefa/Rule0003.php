<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger  =O arquivo somente pode receber tarefas arquivísitcas!
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
            Tarefa::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tarefa|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $especiesArquivistas = [
            $this->parameterBag->get('constantes.entidades.genero_tarefa.const_1'),
            $this->parameterBag->get('constantes.entidades.genero_tarefa.const_2')
        ];
        if (($this->parameterBag->get('constantes.entidades.especie_setor.const_2') === $restDto->getSetorResponsavel()?->getEspecieSetor()->getNome()) &&
            (!in_array($restDto->getEspecieTarefa()?->getGeneroTarefa()->getNome(), $especiesArquivistas))) {
            $this->rulesTranslate->throwException('tarefa', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
