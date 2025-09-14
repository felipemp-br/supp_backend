<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|DesentranhamentoDto $desentranhamentoDto;

    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|Documento $documento;

    private MockObject|Juntada $juntada;

    private MockObject|OrigemDados $origemDados;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->desentranhamentoDto = $this->createMock(DesentranhamentoDto::class);
        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->documento = $this->createMock(Documento::class);
        $this->juntada = $this->createMock(Juntada::class);
        $this->origemDados = $this->createMock(OrigemDados::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate
        );
    }

    public function testDocumentoIntegracao(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('TESTE');

        $this->documento->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->juntada->expects(self::exactly(2))
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->desentranhamentoDto->expects(self::exactly(2))
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
    public function testDocumentoNaoIntegracao(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('SICAU');

        $this->documento->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->juntada->expects(self::exactly(2))
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->desentranhamentoDto->expects(self::exactly(2))
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
