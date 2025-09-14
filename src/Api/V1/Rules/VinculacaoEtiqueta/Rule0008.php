<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0008.php.
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
 * Class Rule0008.
 *
 * @descSwagger=O usuário só pode criar no máximo 100 etiquetas por modalidade
 * @classeSwagger=Rule0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource;

    /**
     * Rule0008 constructor.
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
        $qtdEtiquetaModalidade = 0;
        $qtdEtiquetaModalidadeOrgaoCentral = 0;

        /* @var VinculacaoEtiqueta $restDto */
        if ($restDto->getUsuario()) {
            $qtdEtiquetaModalidade = $this->vinculacaoEtiquetaResource
                ->count([
                    'usuario.id' => 'eq:'.$restDto->getUsuario()->getId(),
                    'etiqueta.modalidadeEtiqueta.id' => 'eq:'.$restDto->getEtiqueta()->getModalidadeEtiqueta()->getId(),
                    'etiqueta.ativo' => 'eq:true',
                ]);
        }

        if ($restDto->getSetor()) {
            $qtdEtiquetaModalidade = $this->vinculacaoEtiquetaResource
                ->count([
                    'setor.id' => 'eq:'.$restDto->getSetor()->getId(),
                    'etiqueta.modalidadeEtiqueta.id' => 'eq:'.$restDto->getEtiqueta()->getModalidadeEtiqueta()->getId(),
                    'etiqueta.ativo' => 'eq:true',
                ]);
        }

        if ($restDto->getUnidade()) {
            $qtdEtiquetaModalidade = $this->vinculacaoEtiquetaResource
                ->count([
                    'unidade.id' => 'eq:'.$restDto->getUnidade()->getId(),
                    'etiqueta.modalidadeEtiqueta.id' => 'eq:'.$restDto->getEtiqueta()->getModalidadeEtiqueta()->getId(),
                    'etiqueta.ativo' => 'eq:true',
                ]);
        }

        if ($restDto->getModalidadeOrgaoCentral()) {
            $qtdEtiquetaModalidadeOrgaoCentral = $this->vinculacaoEtiquetaResource
                ->count([
                    'modalidadeOrgaoCentral.id' => 'eq:'.$restDto->getModalidadeOrgaoCentral()->getId(),
                    'etiqueta.modalidadeEtiqueta.id' => 'eq:'.$restDto->getEtiqueta()->getModalidadeEtiqueta()->getId(),
                    'etiqueta.ativo' => 'eq:true',
                ]);
        }

        if ($qtdEtiquetaModalidade >= 100 || $qtdEtiquetaModalidadeOrgaoCentral > 1000) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
