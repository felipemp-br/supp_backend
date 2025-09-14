<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=O usuário não pode compartilhar a tarefa consigo mesmo.
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
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Compartilhamento|RestDtoInterface|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getTarefa() && $restDto->getTarefa()->getId()) {
            if ($restDto->getTarefa()->getUsuarioResponsavel()->getId() === $restDto->getUsuario()->getId()) {
                $this->rulesTranslate->throwException('compartilhamento', '0002');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
