<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0010.php.
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

/**
 * Class Rule0010.
 *
 * @descSwagger=O NUP vinculado já possui outros NUPs vinculados a ele! Não pode haver dupla vinculação!
 * @classeSwagger=Rule0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0010 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    /**
     * Rule0010 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                VinculacaoProcessoRepository $vinculacaoProcessoRepository)
    {
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
        $result = $this->vinculacaoProcessoRepository->findByProcessoPrincipal($restDto->getProcessoVinculado()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0010');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 10;
    }
}
