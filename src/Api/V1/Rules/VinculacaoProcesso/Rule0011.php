<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
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
 * @descSwagger=O NUP principal já se encontra vinculado a outro! Não pode haver dupla vinculação!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        private TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoProcessoRepository = $vinculacaoProcessoRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoProcesso::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoProcesso|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->transactionManager->getContext('remessaDocumentoAvulso', $transactionId)) {
            return true;
        }
        $result = $this->vinculacaoProcessoRepository->findByProcessoVinculado($restDto->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0009');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 11;
    }
}
