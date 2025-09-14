<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRepositorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoRepositorioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=Verifica se a tese já foi criado antes com mesmo nome e modalidade
 * @classeSwagger=Rule0003
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoRepositorioRepository $vinculacaoRepositorioRepository;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoRepositorioRepository $vinculacaoRepositorioRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoRepositorioRepository = $vinculacaoRepositorioRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoRepositorio::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|Repositorio|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* Verificando se Repositorio do tipo "INDIVIDUAL" já existe */
        /* @var VinculacaoRepositorio $restDto */
        if ($restDto->getUsuario() && $restDto->getRepositorio()->getModalidadeRepositorio()) {
            $temRepositorioIndividual = $this->vinculacaoRepositorioRepository
                ->findRepositorioByUsuarioModalidade($restDto->getRepositorio(), $restDto->getUsuario()->getId());
            if ($temRepositorioIndividual) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0005');
            }
        }

        /* Verificando se Repositorio do tipo "SETOR" já existe */
        if ($restDto->getSetor() && $restDto->getRepositorio()->getModalidadeRepositorio()) {
            $temRepositorioSetor = $this->vinculacaoRepositorioRepository
                ->findRepositorioBySetorModalidade($restDto->getRepositorio(), $restDto->getSetor()->getId());
            if ($temRepositorioSetor) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0005');
            }
        }

        /* Verificando se Repositorio do tipo "UNIDADE" já existe */
        if ($restDto->getUnidade() &&
            $restDto->getRepositorio()->getModalidadeRepositorio() &&
            !$restDto->getEspecieSetor()) {
            $temRepositorioUnidade = $this->vinculacaoRepositorioRepository
                ->findRepositorioByUnidadeModalidade($restDto->getRepositorio(), $restDto->getUnidade()->getId());
            if ($temRepositorioUnidade) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0005');
            }
        }

        /* Verificando se Repositorio do tipo "ORGAO CENTRAL" já existe */
        if ($restDto->getModalidadeOrgaoCentral() &&
            $restDto->getRepositorio()->getModalidadeRepositorio() &&
            !$restDto->getEspecieSetor()) {
            $temRepositorioOrgaoCentral = $this->vinculacaoRepositorioRepository
                ->findRepositorioByOrgaoCentralModalidade(
                    $restDto->getRepositorio(),
                    $restDto->getModalidadeOrgaoCentral()->getId()
                );
            if ($temRepositorioOrgaoCentral) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0005');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
