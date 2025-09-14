<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0010.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0010.
 *
 * @descSwagger=Não é permitido alterar o campo TipoDocumento e DescricaoOutros para documentos do Barramento
 * @classeSwagger=Rule0010
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0010 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0010 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /** @var Documento $restDto */
        if ($entity->getOrigemDados() &&
            'BARRAMENTO_PEN' === $entity->getOrigemDados()->getFonteDados()
        ) {
            $this->rulesTranslate->throwException('documento', '0010');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 10;
    }
}
