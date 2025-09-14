<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRepositorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoRepositorioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=Verifica se adição de uma espécie de setor está sendo feita em um repositório de órgão central
 * @classeSwagger=Rule0004
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoRepositorioRepository $vinculacaoRepositorioRepository;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoRepositorioRepository $vinculacaoRepositorioRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoRepositorioRepository = $vinculacaoRepositorioRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoRepositorio::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|Repositorio|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* Verifica se a vinculação é uma adição de espécie de setor */
        if ($restDto->getEspecieSetor()) {
            $isRepositorioOrgaoCentral = $this->vinculacaoRepositorioRepository->findIsRepositorioOrgaoCentral(
                $restDto->getRepositorio()->getId()
            );
            if (!$isRepositorioOrgaoCentral) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0006');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
