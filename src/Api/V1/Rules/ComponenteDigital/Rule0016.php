<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0016.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use function pathinfo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0016.
 *
 * @descSwagger=O componente digital possui extensão não permitida para upload!
 * @classeSwagger=Rule0016
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0016 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ParameterBagInterface $parameterBag;

    /**
     * Rule0016 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
                'skipWhenCommand',
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
        $componenteDigitalConfig = $this->parameterBag->get(
            'supp_core.administrativo_backend.componente_digital_extensions'
        );

        $extensao = strtolower(pathinfo($restDto->getFileName(), PATHINFO_EXTENSION));

        if(!$extensao){
            $extensao = $restDto->getExtensao() ?? $restDto->getMimetype();
        }

        if (!$restDto->getOrigemDados()
            && (!isset($componenteDigitalConfig[$extensao]['allowUpload']) ||
                !$componenteDigitalConfig[$extensao]['allowUpload'])
            && !$restDto->getDocumento()->getRelatorio()
        ) {
            $this->rulesTranslate->throwException('componenteDigital', '0016');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
