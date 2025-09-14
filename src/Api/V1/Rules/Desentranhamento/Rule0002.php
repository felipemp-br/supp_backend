<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Desentranhamento/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Documento está vinculado a outro e não pode ser desentranhado!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoDocumentoRepository $vinculacaoDocumentoRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoDocumentoRepository $vinculacaoDocumentoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoDocumentoRepository = $vinculacaoDocumentoRepository;
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
        $result = $this->vinculacaoDocumentoRepository->findByDocumentoVinculado(
            $restDto->getJuntada()->getDocumento()->getId()
        );
        if ($result) {
            $this->rulesTranslate->throwException('desentranhamento', '0002');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
