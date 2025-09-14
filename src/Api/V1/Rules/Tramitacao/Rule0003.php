<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0003.
 *
 * @descSwagger=O NUP está vinculado em outro! Realize a tramitação pelo principal!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private TransactionManager $transactionManager;

    /**
     * Rule0003 constructor.
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
            Tramitacao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Tramitacao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tramitacao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('criacaoTramitacaoProcessoApensado', $transactionId)) {
            return true;
        }

        $result = $this->vinculacaoProcessoRepository->estaApensada($restDto->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('tramitacao', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
