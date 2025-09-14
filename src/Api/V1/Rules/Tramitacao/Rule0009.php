<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule009.php.
 *
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule009.
 *
 * @descSwagger=Pessoa não contém vinculo com o Barramento!
 * @classeSwagger=Rule009
 *
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TransactionManager $transactionManager;

    /**
     * Rule0009 constructor.
     * @param RulesTranslate $rulesTranslate
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            TramitacaoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ('barramento' !== $restDto->getMecanismoRemessa() ||
            $this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        if (!$restDto->getPessoaDestino()->getVinculacaoPessoaBarramento()) {
            $this->rulesTranslate->throwException('tramitacao', '0009');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
