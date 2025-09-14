<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Classificacao $classificacao;

    private MockObject|DesentranhamentoDto $desentranhamentoDto;

    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|Juntada $juntada;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Volume $volume;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->classificacao = $this->createMock(Classificacao::class);
        $this->desentranhamentoDto = $this->createMock(DesentranhamentoDto::class);
        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->juntada = $this->createMock(Juntada::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderesProcesso(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getJuntada')
            ->willReturn($this->juntada);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction');
    }

    public function testUsuarioSemPoderesClassificacao(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true, false);

        $this->classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntada->expects(self::once())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getJuntada')
            ->willReturn($this->juntada);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoderes(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $this->classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntada->expects(self::once())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getJuntada')
            ->willReturn($this->juntada);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction')
        );
    }
}
