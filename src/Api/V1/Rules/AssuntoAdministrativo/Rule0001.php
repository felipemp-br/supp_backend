<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/AssuntoAdministrativo/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\AssuntoAdministrativo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo as AssuntoAdministrativoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\AssuntoAdministrativoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Assunto que tem filhos ativos não pode ser alterados.
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate                  $rulesTranslate
     * @param AssuntoAdministrativoRepository $assuntoAdministrativoRepository
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private AssuntoAdministrativoRepository $assuntoAdministrativoRepository,
    ) {
    }

    public function supports(): array
    {
        return [
            AssuntoAdministrativoDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param AssuntoAdministrativoDTO|RestDtoInterface|null $restDto
     * @param AssuntoAdministrativo|EntityInterface          $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$restDto->getAtivo() && $entity->getAtivo() &&
            $this->assuntoAdministrativoRepository->hasFilhosAtivos($entity->getId())) {
            $this->rulesTranslate->throwException('assuntoAdministrativo', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}