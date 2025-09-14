<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Sigilo/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Sigilo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo as SigiloDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Sigilo\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Sigilo as SigiloEntity;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Sigilo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SigiloDto $sigiloDto;

    private MockObject|SigiloEntity $sigiloEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->sigiloDto = $this->createMock(SigiloDto::class);
        $this->sigiloEntity = $this->createMock(SigiloEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testSemPoderProcesso(): void
    {
        $processo = $this->createMock(Processo::class);
        $this->sigiloDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    public function testSemPoderClassificacao(): void
    {
        $classificacao = $this->createMock(Classificacao::class);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::exactly(2))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->sigiloDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    public function testSemPoderDocumento(): void
    {
        $documento = $this->createMock(Documento::class);
        $this->sigiloDto->expects(self::exactly(2))
            ->method('getDocumento')
            ->willReturn($documento);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    public function testSemPoderJuntadaAtual(): void
    {
        $processo = $this->createMock(Processo::class);

        $volume = $this->createMock(Volume::class);
        $volume->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $juntadaAtual = $this->createMock(Juntada::class);
        $juntadaAtual->expects(self::exactly(2))
            ->method('getVolume')
            ->willReturn($volume);

        $documento = $this->createMock(Documento::class);
        $documento->expects(self::exactly(2))
            ->method('getJuntadaAtual')
            ->willReturn($juntadaAtual);

        $this->sigiloDto->expects(self::exactly(4))
            ->method('getDocumento')
            ->willReturn($documento);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComPoderClassificacao(): void
    {
        $classificacao = $this->createMock(Classificacao::class);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::exactly(2))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->sigiloDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($processo);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComPoderJuntadaAtual(): void
    {
        $processo = $this->createMock(Processo::class);

        $volume = $this->createMock(Volume::class);
        $volume->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($processo);

        $juntadaAtual = $this->createMock(Juntada::class);
        $juntadaAtual->expects(self::exactly(2))
            ->method('getVolume')
            ->willReturn($volume);

        $documento = $this->createMock(Documento::class);
        $documento->expects(self::exactly(2))
            ->method('getJuntadaAtual')
            ->willReturn($juntadaAtual);

        $this->sigiloDto->expects(self::exactly(4))
            ->method('getDocumento')
            ->willReturn($documento);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }
}
