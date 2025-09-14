<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0028.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0028.
 *
 * @descSwagger=O documento não pode conter componentes digitais com tipos de arquivos diferentes!
 * @classeSwagger=Rule0028
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0028 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;



    /**
     * Rule0028 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigital|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        ComponenteDigitalDTO|RestDtoInterface|null $restDto,
        ComponenteDigital|EntityInterface $entity,
        string $transactionId
    ): bool {
        // $componentesDigitais  Collection|null|ComponenteDigital[]
        // Collection do doctrine implementa direta ou indiretamente ArrayAccess e Countable
        $componentesDigitais = $restDto?->getDocumento()?->getComponentesDigitais();
        // Se não tem componente digital vinculado ao documento, OK
        if (empty($componentesDigitais) || count($componentesDigitais) === 0) {
            return true;
        } else {
            // se já tem componente digital, os outros devem ter a mesma extensão do primeiro.
            $componenteDigital = $componentesDigitais[0];
            if (strcasecmp($componenteDigital?->getMimetype(), $restDto?->getMimetype()) === 0) {
                return true;
            } else {
                $this->rulesTranslate->throwException(
                    'componenteDigital',
                    '0028',
                    [$componenteDigital?->getExtensao(),$restDto?->getExtensao()]
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 28;
    }
}
