<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule009.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0010.
 *
 * @descSwagger=Apenas processos administrativos podem ser enviados via barramento!
 * @classeSwagger=Rule009
 */
class Rule0010 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0010 constructor.
     *
     * @param RulesTranslate     $rulesTranslate
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            TramitacaoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param TramitacaoDTO|RestDtoInterface|null $restDto
     * @param EntityInterface                     $entity
     * @param string                              $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (('barramento' === $restDto->getMecanismoRemessa()) &&
            ('ADMINISTRATIVO' !== $restDto->getProcesso()->getEspecieProcesso()->getGeneroProcesso()->getNome()) &&
            !$restDto->getDataHoraRecebimento()) {
            $this->rulesTranslate->throwException('tramitacao', '0010');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
