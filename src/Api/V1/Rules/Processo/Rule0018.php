<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0018.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;

/**
 * Class Rule0018.
 *
 * @descSwagger=Processos com tarefas abertas não podem ser enviados para o Arquivo
 * @classeSwagger=Rule0018
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0018 implements RuleInterface
{
    /**
     * Rule0018 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly TarefaRepository $tarefaRepository,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        if ($entity->getSetorAtual()->getEspecieSetor()?->getNome()
            !== $this->parameterBag->get('constantes.entidades.especie_setor.const_2')
            && ($restDto->getSetorAtual()?->getEspecieSetor()?->getNome()
                === $this->parameterBag->get('constantes.entidades.especie_setor.const_2'))
        ) {
            $tarefasAbertas = $this->tarefaRepository->findAbertaByProcessoId(
                $entity->getId()
            );

            if ($tarefasAbertas
                && $this->hasNonArquivisiticoTask($tarefasAbertas)
            ) {
                $this->rulesTranslate->throwException('processo', '0019');
            }
        }

        return true;
    }

    /**
     * @param array $tarefas
     *
     * @return bool
     */
    public function hasNonArquivisiticoTask(array $tarefas): bool
    {
        foreach ($tarefas as $tarefa) {
            /** @var TarefaEntity $tarefa */
            if ('ARQUIVÍSTICO' !== $tarefa->getEspecieTarefa()?->getGeneroTarefa()->getNome()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 18;
    }
}
