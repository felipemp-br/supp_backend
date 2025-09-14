<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Transicao/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0011.
 *
 * @descSwagger=O NUP está vinculado em outro! Realize a transição pelo principal!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private TransactionManager $transactionManager;

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoProcessoRepository = $vinculacaoProcessoRepository;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Transicao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoTransicaoProcessoApensado', $transactionId)) {
            return true;
        }

        $result = $this->vinculacaoProcessoRepository->estaApensada($restDto->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('transicao', '0011');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
