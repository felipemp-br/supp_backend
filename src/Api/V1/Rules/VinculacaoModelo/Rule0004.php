<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoModelo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoModeloRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=Verifica se o modelo já foi criado antes com mesmo nome e template
 * @classeSwagger=Rule0004
 */
class Rule0004 implements RuleInterface
{
    /**
     * Rule0004 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private VinculacaoModeloRepository $vinculacaoModeloRepository,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoModeloDTO::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch'
            ],
        ];
    }

    /**
     * @param VinculacaoModeloDTO|RestDtoInterface|null $restDto
     * @param VinculacaoModeloEntity|EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        VinculacaoModeloDTO|RestDtoInterface|null $restDto,
        VinculacaoModeloEntity|EntityInterface $entity,
        string $transactionId
    ): bool {
        /*
         *  regras de permissao para update/delete de um modelo de uso restrito de um determinado
         *  modulo devem ser feitas pelo proprio modulo
         */
        $modelosModuloAdministrativo = [
            $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_1'), // INDIVIDUAL
            $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_2'), // LOCAL
            $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_3'), // NACIONAL
            $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_4'), // EM BRANCO
        ];
        if (!in_array($restDto->getModelo()?->getModalidadeModelo()?->getValor(), $modelosModuloAdministrativo)) {
            return true;
        }

        /* Verificando se Modelo do tipo "INDIVIDUAL" já existe */
        /* @var VinculacaoModelo $restDto */
        if ($restDto->getUsuario() && $restDto->getModelo()->getTemplate()) {
            $temModeloIndividual = $this->vinculacaoModeloRepository
                ->findModeloByUsuarioTemplate($restDto->getModelo(), $restDto->getUsuario()->getId());
            if ($temModeloIndividual) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0007');
            }
        }

        /* Verificando se Modelo do tipo "SETOR" já existe */
        if ($restDto->getSetor() && $restDto->getModelo()->getTemplate()) {
            $temModeloSetor = $this->vinculacaoModeloRepository
                ->findModeloBySetorTemplate($restDto->getModelo(), $restDto->getSetor()->getId());
            if ($temModeloSetor) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0007');
            }
        }

        /* Verificando se Modelo do tipo "UNIDADE" já existe */
        if ($restDto->getUnidade() &&
            $restDto->getModelo()->getTemplate() &&
            !$restDto->getEspecieSetor()) {
            $temModeloUnidade = $this->vinculacaoModeloRepository
                ->findModeloByUnidadeTemplate($restDto->getModelo(), $restDto->getUnidade()->getId());
            if ($temModeloUnidade) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0007');
            }
        }

        /* Verificando se a especie de setor já existe */
        if ($restDto->getUnidade() &&
            $restDto->getEspecieSetor()) {
            $temEspecieUnidade = $this->vinculacaoModeloRepository
                ->findByModeloIdEspecieSetorIdUnidadeId(
                    $restDto->getModelo()->getId(),
                    $restDto->getEspecieSetor()->getId(),
                    $restDto->getUnidade()->getId()
                );
            if ($temEspecieUnidade) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0008');
            }
        }

        /* Verificando se Modelo do tipo "ORGAO CENTRAL" já existe */
        if ($restDto->getModalidadeOrgaoCentral() &&
            $restDto->getModelo()->getTemplate() &&
            !$restDto->getEspecieSetor()) {
            $temModeloOrgaoCentral = $this->vinculacaoModeloRepository
                ->findModeloByOrgaoCentralTemplate(
                    $restDto->getModelo(),
                    $restDto->getModalidadeOrgaoCentral()->getId()
                );
            if ($temModeloOrgaoCentral) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0007');
            }
        }

        /* Verificando se a especie de setor já existe */
        if ($restDto->getModalidadeOrgaoCentral() &&
            $restDto->getEspecieSetor()) {
            $temEspecieModalidadeOrgaoCentral = $this->vinculacaoModeloRepository
                ->findByModeloIdEspecieSetorIdModalidadeOrgaoCentralId(
                    $restDto->getModelo()->getId(),
                    $restDto->getEspecieSetor()->getId(),
                    $restDto->getModalidadeOrgaoCentral()->getId()
                );
            if ($temEspecieModalidadeOrgaoCentral) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0008');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
