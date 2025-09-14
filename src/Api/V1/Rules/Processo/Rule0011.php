<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0011.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger  =Validacao do NUP de acordo com o plugin
 * @classeSwagger=Rule0011
 */
class Rule0011 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private NUPProviderManager $nupProviderManager;

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        NUPProviderManager $nupProviderManager
    ) {
        $this->nupProviderManager = $nupProviderManager;
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'skipWhenCommand',
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getNUP()) {
            $errorMessage = '';
            if (!$this->nupProviderManager->getNupProvider(
                $restDto
            )->validarNumeroUnicoProtocolo($restDto, $errorMessage)) {
                throw new RuleException($errorMessage);
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 11;
    }
}
