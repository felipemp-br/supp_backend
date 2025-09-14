<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=Atividades que encerram tarefas, não podem ser criadas quando há ofício não remetido.
 * @classeSwagger=Rule0004
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            AtividadeDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param AtividadeDTO|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getEncerraTarefa()) {
            foreach ($restDto->getTarefa()->getMinutas() as $minuta) {
                /** @var Documento $minuta */
                if ($minuta->getDocumentoAvulsoRemessa() &&
                        !$minuta->getDocumentoAvulsoRemessa()->getDataHoraRemessa()) {
                    $this->rulesTranslate->throwException('atividade', '0004');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
