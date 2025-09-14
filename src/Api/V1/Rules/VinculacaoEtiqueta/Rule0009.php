<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0009.
 *
 * @descSwagger=O usuário só pode criar no máximo 5 etiquetas por tarefa
 * @classeSwagger=Rule0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    /**
     * Rule0009 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoEtiquetaResource $vinculacaoEtiquetaResource
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoEtiquetaResource = $vinculacaoEtiquetaResource;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDto::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDto|RestDtoInterface|null $restDto
     * @param VinculacaoEtiquetaEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $qtdEtiquetaPorTarefa = 0;

        /* @var VinculacaoEtiqueta $restDto */
        if ($restDto->getTarefa() && !$restDto->getLabel()) {
            $qtdEtiquetaPorTarefa = $this->vinculacaoEtiquetaResource
                ->count([
                    'tarefa.id' => 'eq:'.$restDto->getTarefa()->getId(),
                    'label' => 'isNull'
                ]);
        }

        if ($qtdEtiquetaPorTarefa >= 7) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0009');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
