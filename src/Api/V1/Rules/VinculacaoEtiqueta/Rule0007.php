<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007.
 *
 * @descSwagger=Verifica se a etiqueta já foi criado antes com mesmo nome e modalidade
 * @classeSwagger=Rule0007
 */
class Rule0007 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository;

    /**
     * Rule0007 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoEtiquetaRepository = $vinculacaoEtiquetaRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiqueta::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|Etiqueta|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* Verificando se Etiqueta do tipo "INDIVIDUAL" já existe */
        /* @var VinculacaoEtiqueta $restDto */
        if ($restDto->getUsuario()) {
            $temEtiquetaIndividual = $this->vinculacaoEtiquetaRepository
                ->findEtiquetaByUsuarioModalidade($restDto->getEtiqueta(), $restDto->getUsuario()->getId());
            if ($temEtiquetaIndividual) {
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0007');
            }
        }

        /* Verificando se Etiqueta do tipo "SETOR" já existe */
        if ($restDto->getSetor()) {
            $temEtiquetaSetor = $this->vinculacaoEtiquetaRepository
                ->findEtiquetaBySetorModalidade($restDto->getEtiqueta(), $restDto->getSetor()->getId());
            if ($temEtiquetaSetor) {
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0007');
            }
        }

        /* Verificando se Etiqueta do tipo "UNIDADE" já existe */
        if ($restDto->getUnidade()) {
            $temEtiquetaUnidade = $this->vinculacaoEtiquetaRepository
                ->findEtiquetaByUnidadeModalidade($restDto->getEtiqueta(), $restDto->getUnidade()->getId());
            if ($temEtiquetaUnidade) {
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0007');
            }
        }

        /* Verificando se Etiqueta do tipo "ORGAO CENTRAL" já existe */
        if ($restDto->getModalidadeOrgaoCentral()) {
            $temEtiquetaOrgaoCentral = $this->vinculacaoEtiquetaRepository
                ->findEtiquetaByOrgaoCentralModalidade(
                    $restDto->getEtiqueta(),
                    $restDto->getModalidadeOrgaoCentral()->getId()
                );
            if ($temEtiquetaOrgaoCentral) {
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0007');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
