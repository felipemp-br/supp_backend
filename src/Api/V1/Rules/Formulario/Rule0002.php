<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DadosFormulario/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Formulario;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario as FormularioDTO;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\ModelGenerator;
use Swaggest\JsonSchema\Exception\ContentException;
use Swaggest\JsonSchema\InvalidRef;

/**
 * Class Rule0002.
 *
 * @descSwagger  =O json enviado deve ser validado, quanto a forma e conteúdo
 * @classeSwagger=Rule0002
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     * @param ModelGenerator $modelGenerator
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ModelGenerator $modelGenerator,
    ) {
    }

    /**
     * @return array
     */
    #[ArrayShape([FormularioDTO::class => "string[]"])]
    public function supports(): array
    {
        return [
            FormularioDTO::class => [
                'assertCreate',
                'assertUpdate',
                'assertPatch',
            ],
        ];
    }

    /**
     * @param FormularioDTO|RestDtoInterface|null $restDto
     * @param FormularioEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        FormularioDTO|RestDtoInterface|null $restDto,
        FormularioEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($restDto->getId() &&
            ($restDto->getDataSchema() !== $entity->getDataSchema())) {
            try {
                $this->modelGenerator->validateSchema($restDto->getDataSchema());
            } catch (InvalidRef|ContentException|Exception $exception) {
                $this->rulesTranslate->throwException(
                    'formulario',
                    'R0002',
                    [$exception->getMessage()]
                );
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}
