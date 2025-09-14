<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0008.
 *
 * @descSwagger=O usuário não tem poderes para modificar a tarefa!
 * @classeSwagger=Rule0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008 implements RuleInterface
{
    /**
     * Rule0008 constructor.
     */
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly CoordenadorService $coordenadorService,
        private readonly RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly TransactionManager $transactionManager,
        private readonly VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
    ) {
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'assertUpdate',
                'assertPatch',
                'assertUndelete',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            /** @var Usuario $usuario */
            $usuario = $this->tokenStorage->getToken()->getUser();
            // Se for resposta de Documento Avulso, pode
            if ($this->transactionManager->getContext('respostaDocumentoAvulso', $transactionId)) {
                return true;
            }

            // é usuário externo? Não pode.
            if (!$usuario->getColaborador()) {
                $this->rulesTranslate->throwException('tarefa', '0008');
            }

            // É administrador e esta distribuindo tarefas de um usuário? Pode.
            if ($this->authorizationChecker->isGranted('ROLE_ADMIN') &&
            $this->transactionManager->getContext('distribuicaoTarefasUsuario', $transactionId)) {
                return true;
            }

            // é o usuário responsável? Pode.
            if ($entity->getUsuarioResponsavel()->getId() === $usuario->getId()) {
                return true;
            }

            // é o usuário que criou a tarefa? Pode.
            if ($entity->getCriadoPor() &&
                ($entity->getCriadoPor()->getId() === $usuario->getId())) {
                return true;
            }

            // é um assessor do usuário responsável? Pode.
            if ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $entity->getUsuarioResponsavel()->getId(),
                $usuario->getId()
            )) {
                return true;
            }

            // é um coordenador responsável? Pode.
            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$entity->getSetorResponsavel()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$entity->getSetorResponsavel()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$entity->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
                );
            if ($isCoordenador) {
                return true;
            }

            $this->rulesTranslate->throwException('tarefa', '0008');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
