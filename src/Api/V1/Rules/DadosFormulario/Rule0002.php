<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DadosFormulario/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DadosFormulario;

use JetBrains\PhpStorm\ArrayShape;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDTO;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario as DadosFormularioEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\ModelGenerator;
use Swaggest\JsonSchema\Exception;

/**
 * Class Rule0002.
 *
 * @descSwagger  =Valida o DataValue de acordo com o DataSchema do Formulario
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     * @param RulesTranslate $rulesTranslate
     * @param ModelGenerator $modelGenerator
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ModelGenerator $modelGenerator,
    ) {
    }

    #[ArrayShape([DadosFormularioDTO::class => "string[]"])]
    public function supports(): array
    {
        return [
            DadosFormularioDTO::class => [
                'assertCreate',
                'assertUpdate',
                'assertPatch',
            ],
        ];
    }

    /**
     * @param DadosFormularioDTO|RestDtoInterface|null $restDto
     * @param DadosFormularioEntity|EntityInterface    $entity
     * @param string                                   $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        DadosFormularioDTO|RestDtoInterface|null $restDto,
        DadosFormularioEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        try {
            if ($restDto->getFormulario()) {
                $this->modelGenerator->validateSchema(
                    $restDto->getDataValue(),
                    $restDto->getFormulario()->getDataSchema()
                );
            }
        } catch (Exception $e) {
            if (!$restDto->getFormulario()->getAceitaJsonInvalido()) {
                $this->rulesTranslate->throwException(
                    'dadosFormulario',
                    'R0002',
                    [$e->getMessage()]
                );
            }
            $restDto->setInvalido(true);
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
