<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0004.php.
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
 * Class Rule0004.
 *
 * @descSwagger=Tramitação não pode ser recebida, pois já houve o recebimento anteriormente!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeUpdate',
                'beforePatch',
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
        if ($entity->getDataHoraRecebimento() &&
            $restDto->getDataHoraRecebimento() &&
            ($entity->getDataHoraRecebimento() !== $restDto->getDataHoraRecebimento())) {
            $this->rulesTranslate->throwException('tramitacao', '0004');
        }

        if ($entity->getUsuarioRecebimento() &&
            $restDto->getUsuarioRecebimento() &&
            ($entity->getUsuarioRecebimento()->getId() !== $restDto->getUsuarioRecebimento()->getId())) {
            $this->rulesTranslate->throwException('tramitacao', '0004');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
