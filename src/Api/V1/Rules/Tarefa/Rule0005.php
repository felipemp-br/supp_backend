<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005.
 *
 * @descSwagger=O NUP está anexado ou apensado a outro! Abra a tarefa no NUP principal!
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    /**
     * Rule0005 constructor.
     */
    public function __construct(
        protected RulesTranslate $rulesTranslate,
        protected VinculacaoProcessoRepository $vinculacaoProcessoRepository
    ) { }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(Tarefa|RestDtoInterface|null $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoProcessoRepository->estaApensada($restDto->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('tarefa', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 5;
    }
}
