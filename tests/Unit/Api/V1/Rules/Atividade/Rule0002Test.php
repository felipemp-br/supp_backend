<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Atividade/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Atividade;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0002Test.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AtividadeDto $atividadeDto;

    private MockObject|AtividadeEntity $atividadeEntity;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioResponsavel;

    private MockObject|Usuario $usuarioToken;

    private MockObject|VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->atividadeDto = $this->createMock(AtividadeDto::class);
        $this->atividadeEntity = $this->createMock(AtividadeEntity::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioResponsavel = $this->createMock(Usuario::class);
        $this->usuarioToken = $this->createMock(Usuario::class);
        $this->vinculacaoUsuarioRepository = $this->createMock(VinculacaoUsuarioRepository::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->vinculacaoUsuarioRepository,
            $this->coordenadorService
        );
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioResponsavel(): void
    {
        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $this->atividadeDto->expects(self::never())
            ->method('getDocumentos')
            ->willReturn([]);

        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::once())
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testNaoEncerramento(): void
    {
        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(false);

        $this->atividadeDto->expects(self::once())
            ->method('getDocumentos')
            ->willReturn([]);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testUsuarioVinculadoEncerraTarefa(): void
    {
        $this->usuarioToken->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(2);

        $this->tarefa->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::exactly(5))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->atividadeDto->expects(self::exactly(2))
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $vinculacaoUsuario = $this->createMock(VinculacaoUsuario::class);
        $vinculacaoUsuario->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn($vinculacaoUsuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testCoordenadorSetor(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->atividadeDto->expects(self::exactly(4))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testCoordenadorUnidade(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->atividadeDto->expects(self::exactly(4))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testCoordenadorOrgaoCentral(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->atividadeDto->expects(self::exactly(4))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws Exception
     */
    public function testNaoCoordenador(): void
    {
        $this->usuarioToken->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(2);

        $this->tarefa->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn(null);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->atividadeDto->expects(self::exactly(5))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction');
    }
}
