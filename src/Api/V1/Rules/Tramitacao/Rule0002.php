<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=NUP já se encontra no setor de destino!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0002 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
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
        if ($restDto->getSetorDestino() &&
            ($restDto->getSetorDestino()->getId() === $restDto->getProcesso()->getSetorAtual()->getId())) {
            $this->rulesTranslate->throwException('tramitacao', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
