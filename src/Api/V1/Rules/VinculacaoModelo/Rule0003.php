<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoModelo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Verifica se o usuário tem permissão para excluir a VinculacaoModelo
 * @classeSwagger=Rule0003
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0003 implements RuleInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     * @param RulesTranslate                $rulesTranslate
     * @param CoordenadorService            $coordenadorService
     * @param ParameterBagInterface         $parameterBag
     */
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private TokenStorageInterface $tokenStorage,
        private RulesTranslate $rulesTranslate,
        private CoordenadorService $coordenadorService,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoModeloEntity::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param VinculacaoModeloDTO|RestDtoInterface|null $restDto
     * @param VinculacaoModeloEntity|EntityInterface    $entity
     * @param string                                    $transactionId
     *
     * @return bool
     *
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
        if (!in_array($entity->getModelo()?->getModalidadeModelo()?->getValor(), $modelosModuloAdministrativo)) {
            return true;
        }

        /* Modelo do tipo "EM BRANCO" somente ROOT tem permissão */
        if (!$entity->getSetor() &&
            !$entity->getUsuario() &&
            !$entity->getModalidadeOrgaoCentral() &&
            !$entity->getEspecieSetor() &&
            !$entity->getUnidade()) {
            $this->rulesTranslate->throwException('vinculacaoModelo', '0002');
        }

        /* @var VinculacaoModelo $entity */
        if ($entity->getUsuario()) { /* Modelo do tipo "INDIVIDUAL" usuário tem permissão */
            if ($entity->getUsuario()->getId() !==
                $this->tokenStorage->getToken()->getUser()->getId()
            ) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0003');
            }
        }
        /*
         *  Modelo do tipo "SETOR"
         *  Permissão para Coordenador de SETOR, UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($entity->getSetor()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorSetor([$entity->getSetor()]) &&
                !$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$entity->getSetor()->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$entity->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
                    )
            ) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0004');
            }
        }

        /*
         *  Modelo do tipo "UNIDADE"
         *  Permissão para Coordenador de UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($entity->getUnidade()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$entity->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral([$entity->getUnidade()->getModalidadeOrgaoCentral()])
            ) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0005');
            }
        }

        /*
         *  Modelo do tipo ORGAO CENTRAL
         *  Permissão para Coordenador de ÓRGÃO CENTRAL
         * */
        if ($entity->getModalidadeOrgaoCentral()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral(
                [$entity->getModalidadeOrgaoCentral()]
            )) {
                $this->rulesTranslate->throwException('vinculacaoModelo', '0006');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
