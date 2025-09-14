<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Classificacao/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Classificacao\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Repository\ClassificacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|ClassificacaoEntity $classificacaoEntity;

    private MockObject|ClassificacaoRepository $classificacaoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classificacaoEntity = $this->createMock(ClassificacaoEntity::class);
        $this->classificacaoRepository = $this->createMock(ClassificacaoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->classificacaoRepository
        );
    }

    /**
     * @throws RuleException
     */
    public function testClassificacaoInativaSemDocumentos(): void
    {
        $this->classificacaoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->classificacaoEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->classificacaoRepository->expects(self::once())
            ->method('hasProcessoAtivo')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->classificacaoEntity, 'transaction'));
    }

    public function testClassificacaoInativaComDocumentos(): void
    {
        $this->classificacaoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->classificacaoEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->classificacaoRepository->expects(self::once())
            ->method('hasProcessoAtivo')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->classificacaoEntity, 'transaction');
    }
}
