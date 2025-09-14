<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use function pathinfo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0015.
 *
 * @descSwagger=O componente digital não possui uma extensão válida!
 * @classeSwagger=Rule0015
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0015 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ParameterBagInterface $parameterBag;

    private TransactionManager $transactionManager;

    /**
     * Rule0015 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ParameterBagInterface $parameterBag,
        TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->parameterBag = $parameterBag;
        $this->transactionManager = $transactionManager;
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
        if ($this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            return true;
        }

        $componenteDigitalConfig = $this->parameterBag->get(
            'supp_core.administrativo_backend.componente_digital_extensions'
        );

        $extensao = strtolower(pathinfo($restDto->getFileName(), PATHINFO_EXTENSION));

        if(!$extensao || !isset($componenteDigitalConfig[$extensao])){
            $extensao = $restDto->getExtensao() ?? $restDto->getMimetype();

            if ($extensao) {
                foreach ($componenteDigitalConfig as $index => $config) {
                    if (isset($config['mimeType']) && $config['mimeType'] === $restDto->getMimetype()) {
                        $extensao = $index;
                        break;
                    }
                }

                $restDto->setFileName($restDto->getFileName() . '.' . $extensao);
            }
        }

        if (!$extensao || !isset($componenteDigitalConfig[$extensao])) {
            $this->rulesTranslate->throwException('componenteDigital', '0015');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
