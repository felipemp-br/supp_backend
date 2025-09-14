<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Usuario/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger=A nova senha não pode ser a mesma da anterior!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{


    /**
     * Rule0011 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'beforeAlterarSenha',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getPlainPassword() === $restDto->getCurrentPlainPassword()) {
            $this->rulesTranslate->throwException('usuario', '0011');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
