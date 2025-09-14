<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0001.php.
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
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=O usuário não tem direito de criar ou editar a etiqueta da tarefa
 *
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private TokenStorageInterface $tokenStorage;
    private CoordenadorService $coordenadorService;
    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;
    private TransactionManager $transactionManager;
    private CompartilhamentoRepository $compartilhamentoRepository;
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        CoordenadorService $coordenadorService,
        TransactionManager $transactionManager,
        CompartilhamentoRepository $compartilhamentoRepository,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->coordenadorService = $coordenadorService;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
        $this->transactionManager = $transactionManager;
        $this->compartilhamentoRepository = $compartilhamentoRepository;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDto::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
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
        if ($restDto->getTarefa()
            && $this->tokenStorage->getToken()
            && $this->tokenStorage->getToken()->getUser()) {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            $tarefa = $restDto->getTarefa();

            // Se for admin, permite.
            if ($this->authorizationChecker->isGranted('ROLE_ADMIN')
            ) {
                return true;
            }

            // usuário externo apenas pode se estiver respondendo ofício
            if (!$usuario->getColaborador()) {
                foreach ($tarefa->getProcesso()->getDocumentosAvulsos() as $documentosAvulso) {
                    if ($documentosAvulso->getPessoaDestino()) {
                        foreach ($usuario->getVinculacoesPessoasUsuarios() as $vinculacaoPessoaUsuario) {
                            if ($documentosAvulso->getPessoaDestino()->getId() ===
                                $vinculacaoPessoaUsuario->getPessoa()->getId()) {
                                return true;
                            }
                        }
                    }
                }
                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0001');
            } else {
                // é o usuário responsável? Pode.
                if ($tarefa->getUsuarioResponsavel()->getId() === $usuario->getId()) {
                    return true;
                }

                // é o usuário que criou a tarefa e antes da leitura? Pode.
                if ($tarefa->getCriadoPor()
                    && ($tarefa->getCriadoPor()->getId() === $usuario->getId())
                    && !$tarefa->getDataHoraLeitura()) {
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

                // submeteu à aprovação ou assessor submeteu? Pode.
                /** @var Context $atividadeAprovacao */
                $atividadeAprovacao =
                    $this->transactionManager->getContext('atividadeAprovacao', $transactionId);
                if ($atividadeAprovacao
                    && ($atividadeAprovacao->getValue()[0] === $usuario->getId()
                        || $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                            $atividadeAprovacao->getValue()[0],
                            $usuario->getId()
                        ))
                ) {
                    return true;
                }

                // caso é coordenador da tarefa submetida para aprovacao
                if ($atividadeAprovacao) {
                    /** @var \SuppCore\AdministrativoBackend\Entity\Setor $setorAprovacao */
                    $setorAprovacao = $atividadeAprovacao->getValue()[1];

                    $isCoordenador = false;
                    $isCoordenador |= $this->coordenadorService
                        ->verificaUsuarioCoordenadorSetor([$setorAprovacao]);
                    $isCoordenador |= $this->coordenadorService
                        ->verificaUsuarioCoordenadorUnidade([$setorAprovacao->getUnidade()]);
                    $isCoordenador |= $this->coordenadorService
                        ->verificaUsuarioCoordenadorOrgaoCentral(
                            [$setorAprovacao->getUnidade()->getModalidadeOrgaoCentral()]
                        );
                    if ($isCoordenador) {
                        return true;
                    }
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

                // se estiver respondendo a um ofício
                foreach ($tarefa->getDocumentosAvulsos() as $documentosAvulso) {
                    if ($usuario === $documentosAvulso->getUsuarioResposta()) {
                        return true;
                    }
                }

                // É nova Tarefa relacionada a Resposta de Documento Avulso
                /** @var Context $respostaDocumentoAvulso */
                $respostaDocumentoAvulso =
                    $this->transactionManager->getContext('respostaDocumentoAvulso', $transactionId);
                if ($respostaDocumentoAvulso) {
                    return true;
                }

                if ($this->transactionManager->getContext('distribuirOficio', $transactionId)) {
                    return true;
                }


                $this->rulesTranslate->throwException('vinculacao_etiqueta', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
