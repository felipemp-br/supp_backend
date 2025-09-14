<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0003.
 *
 * @descSwagger=O documento está vinculado a outro e não pode ser associado a uma tarefa!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private VinculacaoDocumentoRepository $vinculacaoDocumentoRepository,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Documento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Documento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $contextValue = $this->transactionManager
            ->getContext('converteAnexoEmMinuta', $transactionId)
            ?->getValue();

        if ($contextValue === $restDto->getId()) {
            return true;
        }

        if ($restDto->getTarefaOrigem() &&
            $this->vinculacaoDocumentoRepository->findByDocumentoVinculado($entity->getId())) {
            $this->rulesTranslate->throwException('documento', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
