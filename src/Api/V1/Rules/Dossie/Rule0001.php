<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Dossie;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se existe pessoa cadastrada para o documento principal informado.
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     */
    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            DossieDTO::class => [
                'assertCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DossieDTO|null $restDto
     * @param EntityInterface|Dossie          $entity
     * @param string                          $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(RestDtoInterface | DossieDTO | null $restDto, EntityInterface | Dossie $entity, string $transactionId): bool
    {

        if(!$restDto->getTipoDossie()) {
            $this->rulesTranslate->throwException('dossie', '0001');
        }
        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
