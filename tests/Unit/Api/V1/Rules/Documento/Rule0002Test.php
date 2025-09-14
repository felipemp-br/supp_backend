<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Documento/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Documento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Documento\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Documento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Classificacao $classificacao;

    private MockObject|DocumentoEntity $documentoEntity;

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
        $this->documentoEntity = $this->createMock(DocumentoEntity::class);
        $this->juntada = $this->createMock(Juntada::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderesProcesso(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->documentoEntity->expects(self::exactly(2))
            ->method('getJuntadaAtual')
            ->willReturn($this->juntada);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->documentoEntity, 'transaction');
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

        $this->documentoEntity->expects(self::exactly(2))
            ->method('getJuntadaAtual')
            ->willReturn($this->juntada);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->documentoEntity, 'transaction');
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

        $this->documentoEntity->expects(self::exactly(2))
            ->method('getJuntadaAtual')
            ->willReturn($this->juntada);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->documentoEntity, 'transaction'));
    }
}
