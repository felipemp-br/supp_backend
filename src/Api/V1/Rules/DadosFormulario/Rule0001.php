<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DadosFormulario/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DadosFormulario;

use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDTO;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario as DadosFormularioEntity;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger  =O json enviado deve ser validado, quanto a forma e conteúdo
 * @classeSwagger=Trigger0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
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
     * @throws JsonException
     */
    public function validate(
        DadosFormularioDTO|RestDtoInterface|null $restDto,
        DadosFormularioEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        $invalid = null === $restDto->getDataValue() ||
            (!json_validate($restDto->getDataValue()) && !$restDto->getFormulario()->getAceitaJsonInvalido());

        if ($invalid) {
            $this->rulesTranslate->throwException(
                'dadosFormulario',
                'R0001'
            );
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
