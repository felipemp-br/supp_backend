<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0025.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0025.
 *
 * @descSwagger=Valida se o componente digital foi escaneado pelo anti-virus
 * @classeSwagger=Rule0025
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0025 implements RuleInterface
{
    /**
     * Rule0025 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate,
                                private TokenStorageInterface $tokenStorage,
                                private ParameterBagInterface $parameterBag)
    {
    }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'beforeDownload',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->parameterBag->get('virus_verification_enabled')
            && $this->tokenStorage->getToken()?->getUser() && $entity->getId()) {
            switch ($restDto->getStatusVerificacaoVirus()) {
                case ComponenteDigitalEntity::SVV_INSEGURO:
                    $this->rulesTranslate->throwException('componenteDigital', '0025a');
                    break;
                case ComponenteDigitalEntity::SVV_EXECUTANDO:
                    $this->rulesTranslate->throwException('componenteDigital', '0025b');
                    break;
                case ComponenteDigitalEntity::SVV_ERRO:
                    $this->rulesTranslate->throwException('componenteDigital', '0025c');
                    break;
                case ComponenteDigitalEntity::SVV_PENDENTE === 0:
                    $this->rulesTranslate->throwException('componenteDigital', '0025d');
                    break;
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
