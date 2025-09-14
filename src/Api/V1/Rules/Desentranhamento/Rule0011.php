<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Desentranhamento/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger=Não é permitido realizar o desentranhamento de Ofícios!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0011 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate)
    {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Desentranhamento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
//        COMENTADA PARA PERMITIR DESENTRANHAMENTO DE OFICIOS
//        if ($restDto->getJuntada()->getDocumentoAvulso()) {
//            $this->rulesTranslate->throwException('desentranhamento', '0010');
//        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}
