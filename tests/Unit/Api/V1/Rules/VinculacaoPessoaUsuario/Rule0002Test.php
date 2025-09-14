<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoPessoaUsuario/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoPessoaUsuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoPessoaUsuario\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoPessoaUsuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|Pessoa $pessoa;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Usuario $usuario;

    private MockObject|VinculacaoPessoaUsuarioDto $vinculacaoPessoaUsuarioDto;

    private MockObject|VinculacaoPessoaUsuarioEntity $vinculacaoPessoaUsuarioEntity;

    private MockObject|VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->pessoa = $this->createMock(Pessoa::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->vinculacaoPessoaUsuarioDto = $this->createMock(VinculacaoPessoaUsuarioDto::class);
        $this->vinculacaoPessoaUsuarioEntity = $this->createMock(VinculacaoPessoaUsuarioEntity::class);
        $this->vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->vinculacaoPessoaUsuarioRepository
        );
    }

    public function testUsuarioVinculado(): void
    {
        $this->pessoa->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoPessoaUsuarioDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->vinculacaoPessoaUsuarioDto->expects(self::once())
            ->method('getUsuarioVinculado')
            ->willReturn($this->usuario);

        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findByPessoaAndUsuarioVinculado')
            ->willReturn([[]]);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoPessoaUsuarioDto, $this->vinculacaoPessoaUsuarioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioNaoVinculado(): void
    {
        $this->pessoa->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoPessoaUsuarioDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->vinculacaoPessoaUsuarioDto->expects(self::once())
            ->method('getUsuarioVinculado')
            ->willReturn($this->usuario);

        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findByPessoaAndUsuarioVinculado')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoPessoaUsuarioDto, $this->vinculacaoPessoaUsuarioEntity, 'transaction');
    }
}
