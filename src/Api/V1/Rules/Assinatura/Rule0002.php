<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Assinatura/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=A criação da assinatura digital necessita de certificado e assinatura válidas.
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate
    ) {
    }

    public function supports(): array
    {
        return [
            Assinatura::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param Assinatura|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Assinatura|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getCadeiaCertificadoPEM() ||
            '' === $restDto->getCadeiaCertificadoPEM() ||
            '' === $restDto->getAssinatura() ||
            !$restDto->getAssinatura()) {
            $this->rulesTranslate->throwException('assinatura', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}