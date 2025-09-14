<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0011.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0011.
 *
 * @descSwagger=Não é possível alterar os campos de sugestão da vinculação da etiqueta.
 * @classeSwagger=Rule0011
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011 implements RuleInterface
{

    /**
     * Rule0011 constructor.
     */
    public function __construct(private RulesTranslate $rulesTranslate) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'beforeUpdate',
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
        /** @var $restDto VinculacaoEtiquetaDTO */
        /** @var $entity VinculacaoEtiquetaEntity */
        if ($restDto->getSugestao() !== $entity->getSugestao()
            || ($entity->getDataHoraAprovacaoSugestao()
                && $entity->getDataHoraAprovacaoSugestao() !== $restDto->getDataHoraAprovacaoSugestao()
                )
            || ($entity->getUsuarioAprovacaoSugestao()
                && $entity->getUsuarioAprovacaoSugestao()->getId() !== $restDto->getUsuarioAprovacaoSugestao()?->getId()
            )) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0011');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
