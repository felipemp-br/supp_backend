<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Classificacao;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ClassificacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Apenas classificações inativas e sem filhos podem ser apagadas.
 * @classeSwagger=Rule0003
 *
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private ClassificacaoRepository $classificacaoRepository;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ClassificacaoRepository $classificacaoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->classificacaoRepository = $classificacaoRepository;
    }

    public function supports(): array
    {
        return [
            ClassificacaoEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getAtivo() ||
            $this->classificacaoRepository->hasClassificacaoFilho($entity->getId())) {
            $this->rulesTranslate->throwException('classificacao', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 3;
    }
}
