<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DadosFormulario/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Formulario;

use JetBrains\PhpStorm\ArrayShape;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario as FormularioDTO;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use JsonException;

/**
 * Class Rule0001.
 *
 * @descSwagger  =O json enviado deve ser validado, quanto a forma e conteúdo
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate
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
        $dataSchema = $restDto->getDataSchema();
        if (is_null($dataSchema)) {
            $this->rulesTranslate->throwException(
                'formulario',
                'R0001'
            );
        }

        try {
            json_decode($dataSchema, true, 512, JSON_THROW_ON_ERROR) ?:
                throw new JsonException();
        } catch (JsonException) {
            $this->rulesTranslate->throwException(
                'formulario',
                'R0001a'
            );
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
