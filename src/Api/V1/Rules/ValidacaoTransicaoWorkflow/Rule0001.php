<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ValidacaoTransicaoWorkflow/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ValidacaoTransicaoWorkflow;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\ValidacaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se a criação da tarefa atende as validações definidas na transição do workflow!
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{

    /**
     * Rule0001 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private ValidacaoTransicaoWorkflowRepository $validacaoTransicaoWorkflowRepository,
                                private TokenStorageInterface $tokenStorage) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|TarefaDTO|null $restDto
     * @param EntityInterface|TarefaEntity $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|TarefaDTO|null $restDto,
        EntityInterface|TarefaEntity $entity,
        string $transactionId
    ): bool
    {
        if (!$restDto?->getVinculacaoWorkflow()?->getId()) {
            return true;
        }

        $result = $this->validacaoTransicaoWorkflowRepository->getValidacaoTransicaoEspecieTarefaTo(
            $restDto->getVinculacaoWorkflow()->getTarefaAtual(),
            $restDto->getEspecieTarefa()
        );

        foreach ($result as $validacaoTransicaoWorkflow) {
            $contexto = json_decode($validacaoTransicaoWorkflow->getContexto(), true);

            if (isset($contexto['setorOrigemId']) && $restDto->getSetorOrigem()->getId() != $contexto['setorOrigemId']) {
                $this->rulesTranslate->throwException('validacaoTransicaoWorkflow', '0001-0001');
            }

            if (
                isset($contexto['unidadeId'])
                && $restDto->getSetorOrigem()->getUnidade()->getId() != $contexto['unidadeId']
            ) {
                $this->rulesTranslate->throwException('validacaoTransicaoWorkflow', '0001-0002');
            }

            if (
                isset($contexto['criadoPorId'])
                && $contexto['criadoPorId'] != $this->tokenStorage->getToken()->getUser()->getId()
            ) {
                $this->rulesTranslate->throwException('validacaoTransicaoWorkflow', '0001-0003');
            }

            if (
                isset($contexto['atribuidoParaId'])
                && $contexto['atribuidoParaId'] != $restDto->getUsuarioResponsavel()->getId()
            ) {
                $this->rulesTranslate->throwException('validacaoTransicaoWorkflow', '0001-0004');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
