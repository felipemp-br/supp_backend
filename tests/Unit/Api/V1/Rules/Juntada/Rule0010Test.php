<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Juntada/Rule0010Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada\Rule0010;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0010Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0010Test extends TestCase
{
    private MockObject|Documento $documento;

    private MockObject|JuntadaDto $juntadaDto;

    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documento = $this->createMock(Documento::class);
        $this->juntadaDto = $this->createMock(JuntadaDto::class);
        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0010(
            $this->rulesTranslate
        );
    }

    public function testDocumentoNaoMinuta(): void
    {
        $atividade = $this->createMock(Atividade::class);
        $atividade->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->documento->expects(self::once())
            ->method('getJuntadaAtual')
            ->willReturn($this->juntadaEntity);

        $this->juntadaDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->juntadaDto->expects(self::once())
            ->method('getJuntadaDesentranhada')
            ->willReturn(null);

        $this->juntadaDto->expects(self::exactly(2))
            ->method('getAtividade')
            ->willReturn($atividade);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoMinuta(): void
    {
        $this->documento->expects(self::once())
            ->method('getJuntadaAtual')
            ->willReturn(null);

        $this->juntadaDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction'));
    }
}
