<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use function hash;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0012.
 *
 * @descSwagger=É necessário conhecer o hash corrente de um componente digital para modificar seu conteúdo!
 * @classeSwagger=Rule0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0012 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand'
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // está editando o conteúdo?
        if ($restDto->getConteudo() &&
            hash('SHA256', $restDto->getConteudo()) !== $entity->getHash() &&
            (!$restDto->getHashAntigo() || ($restDto->getHashAntigo() !== $entity->getHash()))) {
            $this->rulesTranslate->throwException('componenteDigital', '0012');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
