<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Interessado/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Interessado;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeInteressado as ModalidadeInteressadoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Interessado\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado as ModalidadeInteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Interessado;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|InteressadoDto $interessadoDto;

    private MockObject|InteressadoEntity $interessadoEntity;

    private MockObject|ModalidadeInteressadoDto $modalidadeInteressadoDto;

    private MockObject|ModalidadeInteressadoEntity $modalidadeInteressadoEntity;

    private MockObject|OrigemDados $origemDados;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->interessadoDto = $this->createMock(InteressadoDto::class);
        $this->interessadoEntity = $this->createMock(InteressadoEntity::class);
        $this->modalidadeInteressadoDto = $this->createMock(ModalidadeInteressadoDto::class);
        $this->modalidadeInteressadoEntity = $this->createMock(ModalidadeInteressadoEntity::class);
        $this->origemDados = $this->createMock(OrigemDados::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testInteressadoIntegracao(): void
    {
        $this->modalidadeInteressadoDto->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->interessadoDto->expects(self::once())
            ->method('getModalidadeInteressado')
            ->willReturn($this->modalidadeInteressadoDto);

        $this->modalidadeInteressadoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->interessadoEntity->expects(self::once())
            ->method('getModalidadeInteressado')
            ->willReturn($this->modalidadeInteressadoEntity);

        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('TRF');

        $this->interessadoEntity->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->interessadoDto, $this->interessadoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testInteressadoNaoIntegracao(): void
    {
        $this->modalidadeInteressadoDto->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->interessadoDto->expects(self::any())
            ->method('getModalidadeInteressado')
            ->willReturn($this->modalidadeInteressadoDto);

        $this->modalidadeInteressadoEntity->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->interessadoEntity->expects(self::any())
            ->method('getModalidadeInteressado')
            ->willReturn($this->modalidadeInteressadoEntity);

        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('TRF');

        $this->interessadoEntity->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->interessadoDto, $this->interessadoEntity, 'transaction'));
    }
}
