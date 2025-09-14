<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/DocumentoAvulso/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoAvulso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoAvulso\Rule0002;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
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
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoAvulso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|DocumentoAvulsoDto $documentoAvulsoDto;

    private MockObject|DocumentoAvulsoEntity $documentoAvulsoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioLogado;

    private MockObject|Usuario $usuarioResponsavel;

    private MockObject|VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->documentoAvulsoDto = $this->createMock(DocumentoAvulsoDto::class);
        $this->documentoAvulsoEntity = $this->createMock(DocumentoAvulsoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);
        $this->usuarioResponsavel = $this->createMock(Usuario::class);
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
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->documentoAvulsoDto->expects(self::once())
            ->method('getTarefaOrigem')
            ->willReturn($this->tarefa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->documentoAvulsoDto, $this->documentoAvulsoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAcessor(): void
    {
        $this->usuarioLogado->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(22);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->documentoAvulsoDto->expects(self::exactly(5))
            ->method('getTarefaOrigem')
            ->willReturn($this->tarefa);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->tarefa->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $vinculacaoUsuario = $this->createMock(VinculacaoUsuario::class);
        $vinculacaoUsuario->expects(self::once())
            ->method('getCriaOficio')
            ->willReturn(true);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn($vinculacaoUsuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->documentoAvulsoDto, $this->documentoAvulsoEntity, 'transaction'));
    }

    public function testSemPermissao(): void
    {
        $this->usuarioLogado->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(22);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->documentoAvulsoDto->expects(self::exactly(5))
            ->method('getTarefaOrigem')
            ->willReturn($this->tarefa);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->tarefa->expects(self::exactly(3))
            ->method('getSetorResponsavel')
            ->willReturn($setor);

        $this->tarefa->expects(self::exactly(2))
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuarioResponsavel);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $vinculacaoUsuario = $this->createMock(VinculacaoUsuario::class);
        $vinculacaoUsuario->expects(self::once())
            ->method('getCriaOficio')
            ->willReturn(false);

        $this->vinculacaoUsuarioRepository->expects(self::once())
            ->method('findByUsuarioAndUsuarioVinculado')
            ->willReturn($vinculacaoUsuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->documentoAvulsoDto, $this->documentoAvulsoEntity, 'transaction');
    }
}
