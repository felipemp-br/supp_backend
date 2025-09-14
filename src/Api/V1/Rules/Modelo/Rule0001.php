<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Modelo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o usuário tem permissão para alterar o Modelo.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     */
    public function __construct(private TokenStorageInterface $tokenStorage,
                                private RulesTranslate $rulesTranslate,
                                private CoordenadorService $coordenadorService,
                                private ParameterBagInterface $parameterBag,
                                private AuthorizationCheckerInterface $authorizationChecker,
                                private TransactionManager $transactionManager) {
    }

    public function supports(): array
    {
        return [
            ModeloDTO::class => [
                'skipWhenCommand',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param ModeloDTO|RestDtoInterface|null $restDto
     * @param EntityInterface|ModeloEntity $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        ModeloDTO|RestDtoInterface|null $restDto,
        ModeloEntity|EntityInterface $entity,
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
        if (!in_array($entity->getModalidadeModelo()->getValor(), $modelosModuloAdministrativo)) {
            return true;
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($this->transactionManager->getContext('promoverModelo', $transactionId)) {
            return true;
        }

        $isCoordenador = false;
        $setores = $entity->getVinculacoesModelos()
            ->filter(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getSetor())
            ->map(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getSetor())
            ->toArray();

        /*
         * se for de Setor, permissão para coordenador do setor, da unidade ou do orgao central
         */
        foreach ($setores as $setor) {
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorSetor([$setor]);
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorUnidade([$setor->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$setor->getUnidade()->getModalidadeOrgaoCentral()]);
        }

        $unidades = $entity->getVinculacoesModelos()
            ->filter(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getUnidade())
            ->map(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getUnidade())
            ->toArray();
        /*
         * se for de Unidade, permissão para coordenador da unidade ou do orgao central
         */

        foreach ($unidades as $unidade) {
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorUnidade([$unidade]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$unidade->getModalidadeOrgaoCentral()]);
        }

        $orgaosCentrais = $entity->getVinculacoesModelos()
            ->filter(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getModalidadeOrgaoCentral())
            ->map(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getModalidadeOrgaoCentral())
            ->toArray();

        /*
         * se for de OrgaoCentral, permissão para coordenador do orgao central
         */
        foreach ($orgaosCentrais as $orgaoCentral) {
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$orgaoCentral]);
        }

        if (!$isCoordenador && !$this->isUsuario($entity)) {
            $this->rulesTranslate->throwException('modelo', '0001');
        }

        return true;
    }

    private function isUsuario(ModeloEntity $entity): bool
    {
        $idUsuariosVinculados = $entity->getVinculacoesModelos()
            ->filter(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getUsuario())
            ->map(fn (VinculacaoModelo $vinculacaoModelo) => $vinculacaoModelo->getUsuario()->getId())
            ->toArray();

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return in_array($this->tokenStorage->getToken()->getUser()->getId(), $idUsuariosVinculados);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
