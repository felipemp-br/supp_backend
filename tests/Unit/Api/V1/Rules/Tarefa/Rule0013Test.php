<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0013Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AfastamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0013;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0013Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013Test extends TestCase
{
    private MockObject|AfastamentoResource $afastamentoResource;

    private MockObject|Lotacao $lotacao;

    private MockObject|LotacaoResource $lotacaoResource;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setorResponsavel;

    private MockObject|TarefaDto $tarefaDto;

    private MockObject|TarefaEntity $tarefaEntity;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|Usuario $usuarioToken;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->afastamentoResource = $this->createMock(AfastamentoResource::class);
        $this->lotacao = $this->createMock(Lotacao::class);
        $this->lotacaoResource = $this->createMock(LotacaoResource::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorResponsavel = $this->createMock(Setor::class);
        $this->tarefaDto = $this->createMock(TarefaDto::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->usuarioToken = $this->createMock(Usuario::class);

        $this->rule = new Rule0013(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->afastamentoResource,
            $this->lotacaoResource,
            $this->transactionManager
        );
    }

    public function testNaoDistruidor(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $colaboradorLotacao = $this->createMock(Colaborador::class);
        $colaboradorLotacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $setorLotacao = $this->createMock(Setor::class);
        $setorLotacao->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getColaborador')
            ->willReturn($colaboradorLotacao);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getSetor')
            ->willReturn($setorLotacao);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getDistribuidor')
            ->willReturn(true);

        $lotacoes = new ArrayCollection();
        $lotacoes->add($this->lotacao);

        $colaboradorResponsavel = $this->createMock(Colaborador::class);
        $colaboradorResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $usuarioResponsavel->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaboradorResponsavel);

        $usuarioResponsavelEntity = $this->createMock(Usuario::class);
        $usuarioResponsavelEntity->expects(self::once())
            ->method('getId')
            ->willReturn(100);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->setorResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->setorResponsavel->expects(self::once())
            ->method('getApenasDistribuidor')
            ->willReturn(true);

        $this->setorResponsavel->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $colaboradorToken = $this->createMock(Colaborador::class);

        $colaboradorToken->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaboradorToken);

        $afastamentoRepository = $this->createMock(AfastamentoRepository::class);
        $afastamentoRepository->expects(self::once())
            ->method('findAfastamento')
            ->willReturn(false);

        $this->afastamentoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($afastamentoRepository);

        $lotacaoRepository = $this->createMock(LotacaoRepository::class);
        $lotacaoRepository->expects(self::once())
            ->method('findIsDistribuidor')
            ->willReturn(false);

        $this->lotacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($lotacaoRepository);

        $this->tarefaDto->expects(self::once())
            ->method('getVisited')
            ->willReturn(['setorResponsavel']);

        $this->tarefaDto->expects(self::exactly(3))
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->tarefaDto->expects(self::exactly(4))
            ->method('getSetorResponsavel')
            ->willReturn($this->setorResponsavel);

        $this->tarefaDto->expects(self::once())
            ->method('getDataHoraFinalPrazo')
            ->willReturn(new \DateTime());

        $this->tarefaEntity->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavelEntity);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDistruidor(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $colaboradorLotacao = $this->createMock(Colaborador::class);
        $colaboradorLotacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $setorLotacao = $this->createMock(Setor::class);
        $setorLotacao->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getColaborador')
            ->willReturn($colaboradorLotacao);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getSetor')
            ->willReturn($setorLotacao);

        $this->lotacao->expects(self::atLeast(1))
            ->method('getDistribuidor')
            ->willReturn(true);

        $lotacoes = new ArrayCollection();
        $lotacoes->add($this->lotacao);

        $colaboradorResponsavel = $this->createMock(Colaborador::class);
        $colaboradorResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $usuarioResponsavel->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaboradorResponsavel);

        $usuarioResponsavelEntity = $this->createMock(Usuario::class);
        $usuarioResponsavelEntity->expects(self::once())
            ->method('getId')
            ->willReturn(100);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->setorResponsavel->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->setorResponsavel->expects(self::once())
            ->method('getApenasDistribuidor')
            ->willReturn(true);

        $this->setorResponsavel->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $colaboradorToken = $this->createMock(Colaborador::class);

        $colaboradorToken->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->usuarioToken->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaboradorToken);

        $afastamentoRepository = $this->createMock(AfastamentoRepository::class);
        $afastamentoRepository->expects(self::once())
            ->method('findAfastamento')
            ->willReturn(false);

        $this->afastamentoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($afastamentoRepository);

        $lotacaoRepository = $this->createMock(LotacaoRepository::class);
        $lotacaoRepository->expects(self::once())
            ->method('findIsDistribuidor')
            ->willReturn(true);

        $this->lotacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($lotacaoRepository);

        $this->tarefaDto->expects(self::once())
            ->method('getVisited')
            ->willReturn(['setorResponsavel']);

        $this->tarefaDto->expects(self::exactly(3))
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->tarefaDto->expects(self::exactly(4))
            ->method('getSetorResponsavel')
            ->willReturn($this->setorResponsavel);

        $this->tarefaDto->expects(self::once())
            ->method('getDataHoraFinalPrazo')
            ->willReturn(new \DateTime());

        $this->tarefaEntity->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavelEntity);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testContextoAtividadeAprovacao(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($this->createMock(Context::class));

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }
}
