<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Aviso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Aviso as AvisoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Aviso;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoAviso;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o usuário tem permissão para alterar o Aviso.
 */
class Rule0001 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    private CoordenadorService $coordenadorService;

    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RulesTranslate $rulesTranslate,
        CoordenadorService $coordenadorService,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->rulesTranslate = $rulesTranslate;
        $this->coordenadorService = $coordenadorService;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            AvisoDTO::class => [
                'skipWhenCommand',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param EntityInterface|Aviso $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $isCoordenador = false;

        $setores = $entity->getVinculacoesAvisos()
           ->filter(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getSetor())
           ->map(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getSetor())
           ->toArray();

        foreach ($setores as $setor) {
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorSetor([$setor]);
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorUnidade([$setor->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$setor->getUnidade()->getModalidadeOrgaoCentral()]);
        }

        $unidades = $entity->getVinculacoesAvisos()
            ->filter(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getUnidade())
            ->map(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getUnidade())
            ->toArray();
        /*
         * se for de Unidade, permissão para coordenador da unidade ou do orgao central
         */

        foreach ($unidades as $unidade) {
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorUnidade([$unidade]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$unidade->getModalidadeOrgaoCentral()]);
        }

        $orgaosCentrais = $entity->getVinculacoesAvisos()
            ->filter(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getModalidadeOrgaoCentral())
            ->map(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getModalidadeOrgaoCentral())
            ->toArray();

        /*
         * se for de OrgaoCentral, permissão para coordenador do orgao central
         */
        foreach ($orgaosCentrais as $orgaoCentral) {
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$orgaoCentral]);
        }

        if (!$isCoordenador && !$this->isUsuario($entity)) {
            $this->rulesTranslate->throwException('aviso', '0001');
        }

        return true;
    }

    private function isUsuario(Aviso $entity): bool
    {
        $idUsuariosVinculados = $entity->getVinculacoesAvisos()
            ->filter(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getUsuario())
            ->map(fn (VinculacaoAviso $vinculacaoAviso) => $vinculacaoAviso->getUsuario()->getId())
            ->toArray();

        return in_array($this->tokenStorage->getToken()->getUser()->getId(), $idUsuariosVinculados);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
