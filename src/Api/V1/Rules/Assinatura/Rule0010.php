<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0010.
 *
 * @descSwagger=Caso o documento seja um modelo ou repositório, não será possível assiná-lo.
 * @classeSwagger=Rule0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0010 implements RuleInterface
{
    /**
     * Rule0010 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate
    ) { }

    public function supports(): array
    {
        return [
            Assinatura::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getComponenteDigital()?->getDocumento()?->getModelo()?->getId()) {
            $this->rulesTranslate->throwException('assinatura', '0010');
        }

        if ($restDto->getComponenteDigital()?->getDocumento()?->getRepositorio()?->getId()) {
            $this->rulesTranslate->throwException('assinatura', '0012');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 10;
    }
}