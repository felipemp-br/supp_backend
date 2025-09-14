<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0007Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0007;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007Test extends TestCase
{
    private MockObject|DesentranhamentoDto $desentranhamentoDto;

    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|Juntada $juntada;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->desentranhamentoDto = $this->createMock(DesentranhamentoDto::class);
        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->juntada = $this->createMock(Juntada::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0007(
            $this->rulesTranslate
        );
    }

    public function testDocumentoJaFoiDesentranhado(): void
    {
        $this->juntada->expects(self::once())
            ->method('getAtivo')
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

    /**
     * @throws RuleException
     */
    public function testDocumentoNaoDesentranhado(): void
    {
        $this->juntada->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

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
