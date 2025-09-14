<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Usuario/Rule0008.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0008.
 *
 * @descSwagger=A imagem de perfil do usuário deve ter uma extensão válida!
 * @classeSwagger=Rule0008
 */
class Rule0008 implements RuleInterface
{
    private array $mimeTypesPermitidos = [
        'image/pjpeg',
        'image/jpeg',
    ];

    /**
     * Rule0008 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private ParameterBagInterface $parameterBag)
    {
    }

    public function supports(): array
    {
        return [
            Usuario::class => [
                'createUpdate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getImgPerfil()) {
            $extensao = strtolower(pathinfo($restDto->getImgPerfil()->getFileName(), PATHINFO_EXTENSION));
            $componenteDigitalConfig = $this->parameterBag->get(
                'supp_core.administrativo_backend.componente_digital_extensions'
            );

            $permitido = (bool) array_map(
                fn ($mimeType) => $mimeType === $componenteDigitalConfig[$extensao]['mimeType'],
                $this->mimeTypesPermitidos
            );

            if (!$permitido) {
                $this->rulesTranslate->throwException('usuario', '0008');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
