<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoUsuario/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoUsuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoUsuario as VinculacaoUsuarioDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoUsuario\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario as VinculacaoUsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoUsuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Usuario $usuario;

    private MockObject|Usuario $usuarioVinculado;

    private MockObject|VinculacaoUsuarioDto $vinculacaoUsuarioDto;

    private MockObject|VinculacaoUsuarioEntity $vinculacaoUsuarioEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->usuarioVinculado = $this->createMock(Usuario::class);
        $this->vinculacaoUsuarioDto = $this->createMock(VinculacaoUsuarioDto::class);
        $this->vinculacaoUsuarioEntity = $this->createMock(VinculacaoUsuarioEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate
        );
    }

    public function testUsuarioIgual(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoUsuarioDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->vinculacaoUsuarioDto->expects(self::once())
            ->method('getUsuarioVinculado')
            ->willReturn($this->usuarioVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoUsuarioDto, $this->vinculacaoUsuarioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioDiferente(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->vinculacaoUsuarioDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->vinculacaoUsuarioDto->expects(self::once())
            ->method('getUsuarioVinculado')
            ->willReturn($this->usuarioVinculado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoUsuarioDto, $this->vinculacaoUsuarioEntity, 'transaction')
        );
    }
}
