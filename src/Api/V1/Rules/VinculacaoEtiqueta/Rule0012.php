<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0012.
 *
 * @descSwagger=Não é possível aprovar sugestões novamente.
 * @classeSwagger=Rule0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012 implements RuleInterface
{

    /**
     * Rule0012 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'beforeAprovarSugestao',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($entity->getUsuarioAprovacaoSugestao()) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0012');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
