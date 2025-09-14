<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoProcesso/Rule0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005.
 *
 * @descSwagger=Um Processo/Documento Avulso não pode ser vinculado a ele mesmo!
 * @classeSwagger=Rule0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0005 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
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
        if ($restDto->getProcesso()->getId() && $restDto->getProcessoVinculado()->getId() &&
            $restDto->getProcesso()->getId() === $restDto->getProcessoVinculado()->getId()) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0005');
        }

        if ($restDto->getProcesso()->getUuid() && $restDto->getProcessoVinculado()->getUuid() &&
            $restDto->getProcesso()->getUuid() === $restDto->getProcessoVinculado()->getUuid()) {
            $this->rulesTranslate->throwException('vinculacaoProcesso', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 5;
    }
}
