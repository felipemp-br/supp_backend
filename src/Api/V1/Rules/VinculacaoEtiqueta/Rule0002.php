<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=O usuário não tem direito de excluir a etiqueta da tarefa
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private TokenStorageInterface $tokenStorage;
    private CoordenadorService $coordenadorService;
    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;
    private CompartilhamentoRepository $compartilhamentoRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        CoordenadorService $coordenadorService,
        CompartilhamentoRepository $compartilhamentoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->coordenadorService = $coordenadorService;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
        $this->compartilhamentoRepository = $compartilhamentoRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaEntity::class => [
                'beforeDelete',
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
        if ($entity->getTarefa() &&
            $this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            $tarefa = $entity->getTarefa();
            // usuário externo apenas pode se estiver respondendo ofício
            if (!$usuario->getColaborador()) {
                foreach ($tarefa->getDocumentosAvulsos() as $documentosAvulso) {
                    if ($documentosAvulso->getPessoaDestino()) {
                        foreach ($usuario->getVinculacoesPessoasUsuarios() as $vinculacaoPessoaUsuario) {
                            if ($documentosAvulso->getPessoaDestino()->getId() ===
                                $vinculacaoPessoaUsuario->getPessoa()->getId()) {
                                return true;
                            }
                        }
                    }
                }
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0002');
            } else {
                // é o usuário responsável? Pode.
                if ($tarefa->getUsuarioResponsavel()->getId() === $usuario->getId()) {
                    return true;
                }

                // é o usuário que criou a tarefa e antes da leitura? Pode.
                if ($tarefa->getCriadoPor() &&
                    ($tarefa->getCriadoPor()->getId() === $usuario->getId()) &&
                    !$tarefa->getDataHoraLeitura()) {
                    return true;
                }

                // é um assessor do usuário responsável? Pode.
                if ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                    $tarefa->getUsuarioResponsavel()->getId(),
                    $usuario->getId()
                )) {
                    return true;
                }

                // tem compartilhamento da tarefa? Pode.
                if ($this->compartilhamentoRepository->findByTarefaAndUsuario(
                    $tarefa->getId(),
                    $usuario->getId()
                )) {
                    return true;
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
                    return true;
                }

                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0002');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
