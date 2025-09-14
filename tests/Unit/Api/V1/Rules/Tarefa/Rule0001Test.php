<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\TramitacaoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaDto $tarefaDto;

    private MockObject|TarefaEntity $tarefaEntity;

    private MockObject|TokenStorage $tokenStorage;

    private MockObject|TramitacaoResource $tramitacaoResource;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaDto = $this->createMock(TarefaDto::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);
        $this->tokenStorage = $this->createMock(TokenStorage::class);
        $this->tramitacaoResource = $this->createMock(TramitacaoResource::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->vinculacaoPessoaUsuarioResource = $this->createMock(VinculacaoPessoaUsuarioResource::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tramitacaoResource,
            $this->transactionManager,
            $this->tokenStorage,
            $this->vinculacaoPessoaUsuarioResource
        );
    }

    /**
     * @throws RuleException
     */
    public function testContextoCriacaoProcessoBarramento(): void
    {
        $context = $this->createMock(Context::class);
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testNUPSemTramitacao(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $tramitacaoRepository = $this->createMock(TramitacaoRepository::class);
        $tramitacaoRepository->expects(self::once())
            ->method('findTramitacaoPendentePorProcesso')
            ->willReturn(false);

        $this->tramitacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($tramitacaoRepository);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->tarefaDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testNUPTramitacaoExternaComPessoaVinculadaConveniada(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $tramitacaoPendente = $this->createMock(Tramitacao::class);
        $tramitacaoPendente->expects(self::once())
            ->method('getPessoaDestino')
            ->willReturn($this->createMock(Pessoa::class));

        $tramitacaoRepository = $this->createMock(TramitacaoRepository::class);
        $tramitacaoRepository->expects(self::once())
            ->method('findTramitacaoPendentePorProcesso')
            ->willReturn($tramitacaoPendente);

        $this->tramitacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($tramitacaoRepository);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $token = $this->createMock(TokenInterface::class);

        $token->expects(self::once())
            ->method('getRoleNames')
            ->willReturn(['ROLE_PESSOA_VINCULADA_CONVENIADA']);

        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->createMock(Usuario::class));

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($token);

        $vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);
        $vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn([[]]);

        $this->vinculacaoPessoaUsuarioResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($vinculacaoPessoaUsuarioRepository);

        $this->tarefaDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testNUPTramitacaoExternaSemPessoaVinculadaConveniada(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $tramitacaoPendente = $this->createMock(Tramitacao::class);
        $tramitacaoPendente->expects(self::once())
            ->method('getPessoaDestino')
            ->willReturn($this->createMock(Pessoa::class));

        $tramitacaoRepository = $this->createMock(TramitacaoRepository::class);
        $tramitacaoRepository->expects(self::once())
            ->method('findTramitacaoPendentePorProcesso')
            ->willReturn($tramitacaoPendente);

        $this->tramitacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($tramitacaoRepository);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $token = $this->createMock(TokenInterface::class);

        $token->expects(self::once())
            ->method('getRoleNames')
            ->willReturn(['ROLE_PESSOA_VINCULADA_CONVENIADA']);

        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->createMock(Usuario::class));

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($token);

        $vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);
        $vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(false);

        $this->vinculacaoPessoaUsuarioResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($vinculacaoPessoaUsuarioRepository);

        $this->tarefaDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction');
    }
}
