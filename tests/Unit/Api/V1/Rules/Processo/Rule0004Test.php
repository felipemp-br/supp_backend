<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Processo/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Processo\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->authorizationChecker,
        );
    }

    /**
     * @throws RuleException
     */
    public function testConversaoGenero(): void
    {
        $generoProcessoDto = $this->createMock(GeneroProcesso::class);
        $generoProcessoDto->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $generoProcessoEntity = $this->createMock(GeneroProcesso::class);
        $generoProcessoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $especieProcessoDto = $this->createMock(EspecieProcesso::class);
        $especieProcessoDto->expects(self::once())
            ->method('getGeneroProcesso')
            ->willReturn($generoProcessoDto);

        $especieProcessoEntity = $this->createMock(EspecieProcesso::class);
        $especieProcessoEntity->expects(self::once())
            ->method('getGeneroProcesso')
            ->willReturn($generoProcessoEntity);

        $this->processoDto->expects(self::exactly(2))
            ->method('getEspecieProcesso')
            ->willReturn($especieProcessoDto);

        $this->processoEntity->expects(self::once())
            ->method('getEspecieProcesso')
            ->willReturn($especieProcessoEntity);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->processoDto, $this->processoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testSemAlteracaoClassificacao(): void
    {
        $classificacaoDto = $this->createMock(Classificacao::class);
        $classificacaoDto->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $classificacaoEntity = $this->createMock(Classificacao::class);
        $classificacaoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->processoDto->expects(self::exactly(2))
            ->method('getClassificacao')
            ->willReturn($classificacaoDto);

        $this->processoEntity->expects(self::once())
            ->method('getClassificacao')
            ->willReturn($classificacaoEntity);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->processoDto, $this->processoEntity, 'transaction'));
    }

    public function testSemPoderesArquivista(): void
    {
        $classificacaoDto = $this->createMock(Classificacao::class);
        $classificacaoDto->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $classificacaoEntity = $this->createMock(Classificacao::class);
        $classificacaoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->processoDto->expects(self::exactly(2))
            ->method('getClassificacao')
            ->willReturn($classificacaoDto);

        $this->processoEntity->expects(self::once())
            ->method('getClassificacao')
            ->willReturn($classificacaoEntity);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComPoderesArquivista(): void
    {
        $classificacaoDto = $this->createMock(Classificacao::class);
        $classificacaoDto->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $classificacaoEntity = $this->createMock(Classificacao::class);
        $classificacaoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->processoDto->expects(self::exactly(2))
            ->method('getClassificacao')
            ->willReturn($classificacaoDto);

        $this->processoEntity->expects(self::once())
            ->method('getClassificacao')
            ->willReturn($classificacaoEntity);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->processoDto, $this->processoEntity, 'transaction'));
    }
}
