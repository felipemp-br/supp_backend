<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0030.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0030.
 *
 * @descSwagger=Usuário sem critério de Distribuidor não pode redistribuir tarefas!
 * @classeSwagger=Rule0030
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0030 implements RuleInterface
{
    /**
     * Rule0030 constructor.
     */
    public function __construct(
        private readonly CoordenadorService $coordenadorService,
        private readonly RulesTranslate $rulesTranslate,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
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
        // Rule0030: Validação de redistribuição de tarefas
        
        if (!$this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser()) {
            // Sem token ou usuário - permitir
            return true;
        }

        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()->getUser();
        // Usuário logado: {$usuario->getNome()} (ID: {$usuario->getId()})

        // Verifica se é uma redistribuição (mudança de usuário responsável)
        if (!$restDto || !$restDto->getUsuarioResponsavel()) {
            // Sem DTO ou usuário responsável - permitir
            return true;
        }

        // Se o usuário responsável não está mudando, não é redistribuição
        $usuarioResponsavelAtualId = $entity->getUsuarioResponsavel() ? $entity->getUsuarioResponsavel()->getId() : null;
        $novoUsuarioResponsavelId = $restDto->getUsuarioResponsavel() ? $restDto->getUsuarioResponsavel()->getId() : null;
        
        // Verificando redistribuição: {$usuarioResponsavelAtualId} -> {$novoUsuarioResponsavelId}
        
        if ($usuarioResponsavelAtualId === $novoUsuarioResponsavelId) {
            // Não é redistribuição - usuário não mudou
            return true;
        }

        // Verifica se o usuário não é colaborador (usuário externo)
        if (!$usuario->getColaborador()) {
            // Usuário não é colaborador - permitir
            return true;
        }

        // Verifica se o usuário tem critério de distribuidor
        $isDistribuidor = false;

        // Método 1: Verificar por role de distribuidor judicial
        $temRoleDistribuidor = $this->authorizationChecker->isGranted('ROLE_DISTRIBUIDOR_JUDICIAL');

        // Método 2: Verificar pelas lotações do usuário
        $lotacoes = $usuario->getColaborador()->getLotacoes();

        /** @var Lotacao $lotacao */
        foreach ($lotacoes as $lotacao) {
            $ehDistribuidor = $lotacao->getDistribuidor();
            
            if ($ehDistribuidor) {
                $isDistribuidor = true;
                break;
            }
        }

        // Considera distribuidor se tem a role OU se tem lotação como distribuidor
        $isDistribuidor = $temRoleDistribuidor || $isDistribuidor;
        
        // Resultado final: é distribuidor? {($isDistribuidor ? 'SIM' : 'NÃO')}

        // Se não tem critério de distribuidor, bloqueia a redistribuição
        if (!$isDistribuidor) {
            // BLOQUEANDO: usuário sem distribuidor
            $this->rulesTranslate->throwException('tarefa', '0030');
        }

        // Permitindo redistribuição
        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}