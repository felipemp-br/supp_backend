<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0017.php.
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
 * Class Rule0017.
 *
 * @descSwagger=O tamanho máximo permitido para esse arquivo foi superado!
 * @classeSwagger=Rule0017
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0017 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ParameterBagInterface $parameterBag;

    /**
     * Rule0017 constructor.
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
        $documento = $restDto->getDocumento();
        $documentoOrigem = $documento ? $documento->getDocumentoOrigem() : null;
        $componentesDigitais = $documentoOrigem ? $documentoOrigem->getComponentesDigitais() : null;
        $componenteDigital = ($componentesDigitais && count($componentesDigitais) > 0) ? $componentesDigitais[0] : null;
        $origemDados = $componenteDigital ? $componenteDigital->getOrigemDados() : null;

        if (!$origemDados) {
            $componenteDigitalConfig = $this->parameterBag->get(
                'supp_core.administrativo_backend.componente_digital_extensions'
            );

            $extensao = strtolower(pathinfo($restDto->getFileName(), PATHINFO_EXTENSION));

            if ($restDto->getConteudo() &&
                isset($componenteDigitalConfig[$extensao]) &&
                (strlen($restDto->getConteudo()) > $componenteDigitalConfig[$extensao]['maxSizeBytes'])) {
                $this->rulesTranslate->throwException('componenteDigital', '0017');
            }
        }

        return true;

    }

    public function getOrder(): int
    {
        return 2;
    }
}
