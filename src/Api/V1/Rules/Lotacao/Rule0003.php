<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Lotacao/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=O usuário já está lotado nesse setor!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private LotacaoRepository $lotacaoRepository;

    /**
     * Rule0003 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                LotacaoRepository $lotacaoRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->lotacaoRepository = $lotacaoRepository;
    }

    public function supports(): array
    {
        return [
            Lotacao::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Lotacao|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Lotacao|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->lotacaoRepository->findLotacaoBySetorAndColaborador(
            $restDto->getSetor()->getId(),
            $restDto->getColaborador()->getId()
        );
        if ($result) {
            $this->rulesTranslate->throwException('lotacao', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
