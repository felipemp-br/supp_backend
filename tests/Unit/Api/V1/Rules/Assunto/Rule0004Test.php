<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assunto/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assunto;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto as AssuntoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assunto\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assunto;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|AssuntoDto $assuntoDto;

    private MockObject|AssuntoEntity $assuntoEntity;

    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assuntoDto = $this->createMock(AssuntoDto::class);
        $this->assuntoEntity = $this->createMock(AssuntoEntity::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoder(): void
    {
        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->assuntoDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->assuntoDto, $this->assuntoEntity, 'transaction');
    }


    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testUsuarioComPoder(): void
    {
        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->assuntoDto->expects(self::exactly(5))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->assuntoDto, $this->assuntoEntity, 'transaction'));
    }
}
