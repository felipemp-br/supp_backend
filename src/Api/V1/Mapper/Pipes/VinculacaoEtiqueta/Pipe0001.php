<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/VinculacaoEtiqueta/Pipe0001.php.
 *
 * @author Diego Ziquinatti - PGE-RS <diego@pge.rs.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoEtiqueta;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    private TokenStorageInterface $tokenStorage;
    protected AuthorizationCheckerInterface $authorizationChecker;
    private CoordenadorService $coordenadorService;
    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        CoordenadorService $coordenadorService,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->coordenadorService = $coordenadorService;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDTO|RestDtoInterface|null $restDto
     * @param VinculacaoEtiquetaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $restDto->setPodeAlterarConteudo(false);
        $restDto->setPodeExcluir(false);

        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            // Etiquetas de tarefa
            if ($entity->getTarefa()) {
                /** @var Usuario $usuario */
                $usuario = $this->tokenStorage->getToken()->getUser();
                $tarefa = $entity->getTarefa();

                // é o usuário responsável? Pode.
                if ($tarefa->getUsuarioResponsavel()->getId() === $usuario->getId()) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }

                // é o usuário que criou a tarefa e antes da leitura? Pode.
                if ($tarefa->getCriadoPor() &&
                    ($tarefa->getCriadoPor()->getId() === $usuario->getId()) &&
                    !$tarefa->getDataHoraLeitura()) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }

                // é um assessor do usuário responsável? Pode.
                if ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                    $tarefa->getUsuarioResponsavel()->getId(),
                    $usuario->getId()
                )) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }

                // é um coordenador responsável? Pode.
                $isCoordenador = false;
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorSetor([$tarefa->getSetorResponsavel()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorUnidade([$tarefa->getSetorResponsavel()->getUnidade()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$tarefa->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
                    );
                if ($isCoordenador) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }
            }

            // Etiquetas de processo
            if ($entity->getProcesso()) {
                $processo = $entity->getProcesso();
                $usuario = $this->tokenStorage->getToken()->getUser();

                if (!$this->authorizationChecker->isGranted('EDIT', $processo)
                    || ($processo->getClassificacao() &&
                        $processo->getClassificacao()->getId() &&
                        !$this->authorizationChecker->isGranted('EDIT', $processo->getClassificacao()))) {
                    $restDto->setPodeAlterarConteudo(false);
                    $restDto->setPodeExcluir(false);

                    return;
                }

                // é um coordenador responsável? Pode.
                $isCoordenador = false;
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorSetor([$processo->getSetorAtual()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorUnidade([$processo->getSetorAtual()->getUnidade()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$processo->getSetorAtual()->getUnidade()->getModalidadeOrgaoCentral()]
                    );
                if ($isCoordenador) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }

                // nao é coordenador, não pode alterar nem excluir etiqueta
                if ($entity->getCriadoPor() &&
                    $usuario->getId() !== $entity->getCriadoPor()->getId()) {
                    $restDto->setPodeAlterarConteudo(false);
                    $restDto->setPodeExcluir(false);

                    return;
                }

                $restDto->setPodeAlterarConteudo(true);
                $restDto->setPodeExcluir(true);
            }

            // Etiquetas de documento
            if ($entity->getDocumento()) {
                $documento = $entity->getDocumento();
                $usuario = $this->tokenStorage->getToken()->getUser();

                if (!$this->authorizationChecker->isGranted('EDIT', $documento)) {
                    $restDto->setPodeAlterarConteudo(false);
                    $restDto->setPodeExcluir(false);

                    return;
                }

                // é um coordenador responsável? Pode.
                $isCoordenador = false;
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorSetor([$documento->getSetorOrigem()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorUnidade([$documento->getSetorOrigem()->getUnidade()]);
                $isCoordenador |= $this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$documento->getSetorOrigem()->getUnidade()->getModalidadeOrgaoCentral()]
                    );
                if ($isCoordenador) {
                    $restDto->setPodeAlterarConteudo(true);
                    $restDto->setPodeExcluir(true);

                    return;
                }

                // nao é coordenador, não pode alterar nem excluir etiqueta
                if ($entity->getCriadoPor() &&
                    $usuario->getId() !== $entity->getCriadoPor()->getId()) {
                    $restDto->setPodeAlterarConteudo(false);
                    $restDto->setPodeExcluir(false);

                    return;
                }

                $restDto->setPodeAlterarConteudo(true);
                $restDto->setPodeExcluir(true);
            }
        }
    }

    public function getOrder(): int
    {
        return 0001;
    }
}
