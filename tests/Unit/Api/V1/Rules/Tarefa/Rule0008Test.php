<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0008Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0008;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0008Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008Test extends TestCase
{
    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaEntity $tarefaEntity;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|Usuario $usuarioToken;

    private MockObject|VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioToken = $this->createMock(Usuario::class);
        $this->vinculacaoUsuarioRepository = $this->createMock(VinculacaoUsuarioRepository::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->rule = new Rule0008(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->vinculacaoUsuarioRepository,
            $this->coordenadorService,
            $this->transactionManager
        );
    }

    public function testUsuarioExterno(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn(null);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRespostaDocumentoAvulso(): void
    {
        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $context = $this->createMock(Context::class);
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioResponsavel(): void
    {
        $colaborador = $this->createMock(Colaborador::class);
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tarefaEntity->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCriadoPor(): void
    {
        $colaborador = $this->createMock(Colaborador::class);
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->usuarioToken->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->tarefaEntity->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAcessorUsuarioResponsavel(): void
    {
        $colaborador = $this->createMock(Colaborador::class);
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->usuarioToken->expects(self::exactly(3))
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(2);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(3);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn([[]]);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorResponsavel(): void
    {
        $colaborador = $this->createMock(Colaborador::class);
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->usuarioToken->expects(self::exactly(3))
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(2);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(3);

        $unidade = $this->createMock(Setor::class);
        $setorResponsavel = $this->createMock(Setor::class);
        $setorResponsavel->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefaEntity->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setorResponsavel);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn(null);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }

    public function testUsuarioSemPoderes(): void
    {
        $colaborador = $this->createMock(Colaborador::class);
        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->usuarioToken->expects(self::exactly(3))
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(2);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(3);

        $unidade = $this->createMock(Setor::class);
        $setorResponsavel = $this->createMock(Setor::class);
        $setorResponsavel->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefaEntity->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setorResponsavel);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->tarefaEntity->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn(null);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }
}
