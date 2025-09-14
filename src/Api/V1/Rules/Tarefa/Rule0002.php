<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0002.php.
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
 * Class Rule0002.
 *
 * @descSwagger=O NUP está arquivado! Apenas tarefas do gênero arquivístico podem ser criadas! Solicite o desarquivamento por tarefa!
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
        if ($this->parameterBag->get('constantes.entidades.modalidade_fase.const_1') !== $restDto->getProcesso()->getModalidadeFase()->getValor() &&
            $this->parameterBag->get('constantes.entidades.genero_tarefa.const_1') !== $restDto->getEspecieTarefa()->getGeneroTarefa()->getNome()) {
            $this->rulesTranslate->throwException('tarefa', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
