<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Desentranhamento/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0009.
 *
 * @descSwagger=Não foi possível completar a operação porque o arquivo da unidade não foi encontrado!
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private SetorRepository $setorRepository;

    /**
     * Rule0009 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        SetorRepository $setorRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->setorRepository = $setorRepository;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Desentranhamento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ('arquivo' === $restDto->getTipo()) {
            $arquivo = $this->setorRepository->findArquivoInUnidade(
                $restDto->getJuntada()->getVolume()->getProcesso()->getSetorAtual()->getUnidade()->getId()
            );
            if (!$arquivo) {
                $this->rulesTranslate->throwException('desentranhamento', '0009');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 9;
    }
}
