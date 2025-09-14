<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0005;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorDto $setorDto;

    private MockObject|SetorEntity $setorEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorDto = $this->createMock(SetorDto::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate
        );
    }

    public function testSetorComUsuarioLotado(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorEntity->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacoes = new ArrayCollection();
        $lotacoes->add($lotacao);

        $this->setorEntity->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->setorDto, $this->setorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDesativarSemFilhos(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorEntity->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->setorEntity->expects(self::once())
            ->method('getLotacoes')
            ->willReturn(new ArrayCollection());

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUnidade(): void
    {
        $this->setorEntity->expects(self::once())
            ->method('getParent')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }
}
