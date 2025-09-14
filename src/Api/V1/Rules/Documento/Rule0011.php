<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Documento/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Documento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger=Não é permitido converter uma minuta que contém anexos em anexo de outra minuta!
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{

    /**
     * Rule0011 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private VinculacaoDocumentoRepository $vinculacaoDocumentoRepository
    )
    {
    }

    public function supports(): array
    {
        return [
            Documento::class => [
                'beforeConverteMinutaEmAnexo',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->vinculacaoDocumentoRepository->findByDocumento($entity->getId())) {
            $this->rulesTranslate->throwException('documento', '0011');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
