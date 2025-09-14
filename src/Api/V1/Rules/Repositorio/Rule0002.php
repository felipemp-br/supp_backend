<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Repositorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio as RepositorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Repositorio as RepositorioEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se o usuário tem permissão para excluir o repositório.
 * @classeSwagger=Rule0002
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0002 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RulesTranslate $rulesTranslate,
        CoordenadorService $coordenadorService
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->rulesTranslate = $rulesTranslate;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            RepositorioEntity::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param RepositorioDTO|RestDtoInterface|null $restDto
     * @param RepositorioEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $isCoordenador = false;

        $setores = $entity->getVinculacoesRepositorios()
            ->filter(fn (VinculacaoRepositorio $vinculacaoRepositorio) => (bool) $vinculacaoRepositorio->getSetor())
            ->map(fn (VinculacaoRepositorio $vinculacaoRepositorio) => $vinculacaoRepositorio->getSetor())
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

        $unidades = $entity->getVinculacoesRepositorios()
            ->filter(fn (VinculacaoRepositorio $vinculacaoRepositorio) => (bool) $vinculacaoRepositorio->getUnidade())
            ->map(fn (VinculacaoRepositorio $vinculacaoRepositorio) => $vinculacaoRepositorio->getUnidade())
            ->toArray();

        /*
         * se for de Unidade, permissão para coordenador da unidade ou do orgao central
         */

        foreach ($unidades as $unidade) {
            $isCoordenador |= $this->coordenadorService->verificaUsuarioCoordenadorUnidade([$unidade]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$unidade->getModalidadeOrgaoCentral()]);
        }

        $orgaosCentral = $entity->getVinculacoesRepositorios()
            ->filter(fn (VinculacaoRepositorio $vinculacaoRepositorio) => (bool) $vinculacaoRepositorio->getModalidadeOrgaoCentral())
            ->map(fn (VinculacaoRepositorio $vinculacaoRepositorio) => $vinculacaoRepositorio->getModalidadeOrgaoCentral())
            ->toArray();

        /*
         * se for de OrgaoCentral, permissão para coordenador do orgao central
         */
        foreach ($orgaosCentral as $orgaoCentral) {
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral([$orgaoCentral]);
        }

        if (!$isCoordenador && !$this->isUsuario($entity)) {
            $this->rulesTranslate->throwException('repositorio', '0002');
        }

        return true;
    }

    private function isUsuario(RepositorioEntity $entity): bool
    {
        $idUsuariosVinculados = $entity->getVinculacoesRepositorios()
            ->filter(fn (VinculacaoRepositorio $vinculacaoRepositorio) => $vinculacaoRepositorio->getUsuario())
            ->map(fn (VinculacaoRepositorio $vinculacaoRepositorio) => $vinculacaoRepositorio->getUsuario()->getId())
            ->toArray();

        return in_array($this->tokenStorage->getToken()->getUser()->getId(), $idUsuariosVinculados);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
