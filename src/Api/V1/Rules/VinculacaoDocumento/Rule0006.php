<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoDocumento/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006.
 *
 * @descSwagger=O documento principal já se encontra vinculado a outro! Não pode haver dupla vinculação!
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoDocumentoRepository $vinculacaoDocumentoRepository;

    /**
     * Rule0006 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                VinculacaoDocumentoRepository $vinculacaoDocumentoRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoDocumentoRepository = $vinculacaoDocumentoRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoDocumento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->vinculacaoDocumentoRepository->findByDocumentoVinculado($restDto->getDocumento()->getId())) {
            $this->rulesTranslate->throwException('vinculacaoDocumento', '0006');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 6;
    }
}
