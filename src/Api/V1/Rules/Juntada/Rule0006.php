<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006.
 *
 * @descSwagger=O NUP está apensado ou anexado a outro e não pode receber juntadas!
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    /**
     * Rule0006 constructor.
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
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Juntada|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoProcessoRepository->estaApensada($restDto->getVolume()->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('juntada', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}
