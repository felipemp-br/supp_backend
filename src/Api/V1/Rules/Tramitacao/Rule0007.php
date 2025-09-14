<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tramitacao/Rule0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007.
 *
 * @descSwagger=O NUP está vinculado em outro! Realize a tramitação pelo principal!
 * @classeSwagger=Rule0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    /**
     * Rule0007 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                VinculacaoProcessoRepository $vinculacaoProcessoRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoProcessoRepository = $vinculacaoProcessoRepository;
    }

    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param \SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao|RestDtoInterface|null $restDto
     * @param Tramitacao|EntityInterface                                                  $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoProcessoRepository->estaApensada($entity->getProcesso()->getId());
        if ($result) {
            $this->rulesTranslate->throwException('tramitacao', '0005');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 7;
    }
}
