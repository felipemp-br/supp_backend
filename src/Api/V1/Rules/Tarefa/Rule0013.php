<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0013.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0013.
 *
 * @descSwagger=O setor foi configurado para receber tarefas apenas pelos seus distribuidores!
 * @classeSwagger=Rule0013
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private AfastamentoResource $afastamentoResource;

    private LotacaoResource $lotacaoResource;

    /**
     * Rule0013 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        AfastamentoResource $afastamentoResource,
        LotacaoResource $lotacaoResource,
        private TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->afastamentoResource = $afastamentoResource;
        $this->lotacaoResource = $lotacaoResource;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
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
        if ($this->transactionManager->getContext('atividadeAprovacao', $transactionId)) {
            return true;
        }

        // redistribuição apenas
        if (!$restDto->getUsuarioResponsavel() ||
            ($restDto->getUsuarioResponsavel()->getId() !== $entity->getUsuarioResponsavel()->getId())) {
            
            if ($restDto->getSetorResponsavel()->getApenasDistribuidor() &&
                $this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                in_array('setorResponsavel', $restDto->getVisited())) {
                $usuario = $this->tokenStorage->getToken()->getUser();
                $temDistribuidor = false;

                foreach ($restDto->getSetorResponsavel()->getLotacoes() as $lotacao) {
                    if ($lotacao->getDistribuidor() &&
                        (!$this->afastamentoResource->getRepository()->findAfastamento(
                            $lotacao->getColaborador()->getId(),
                            $restDto->getDataHoraFinalPrazo()
                        ))
                    ) {
                        $temDistribuidor = true;
                    }
                }

                $estaLotado = false;

                foreach ($usuario->getColaborador()->getLotacoes() as $lotacao) {
                    if ($lotacao->getSetor()->getId() == $restDto->getSetorResponsavel()->getId()) {
                        $estaLotado = true;
                    }
                }

                if ($temDistribuidor &&
                    (!$estaLotado) &&
                    !$this->lotacaoResource->getRepository()->findIsDistribuidor(
                        $restDto->getUsuarioResponsavel()->getColaborador()->getId(),
                        $restDto->getSetorResponsavel()->getId()
                    )) {
                    $this->rulesTranslate->throwException('tarefa', '0013');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 13;
    }
}
