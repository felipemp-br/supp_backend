<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tramitacao/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TramitacaoDto $tramitacaoDto;

    private MockObject|TramitacaoEntity $tramitacaoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tramitacaoDto = $this->createMock(TramitacaoDto::class);
        $this->tramitacaoEntity = $this->createMock(TramitacaoEntity::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate
        );
    }

    public function testTramitacaoJaRecebidaDataHora(): void
    {
        $this->tramitacaoEntity->expects(self::exactly(2))
            ->method('getDataHoraRecebimento')
            ->willReturn(new DateTime());

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getDataHoraRecebimento')
            ->willReturn(new DateTime());

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }

    public function testTramitacaoJaRecebidaUsuario(): void
    {
        $usuarioDto = $this->createMock(Usuario::class);
        $usuarioEntity = $this->createMock(Usuario::class);

        $usuarioDto->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->tramitacaoEntity->expects(self::exactly(2))
            ->method('getUsuarioRecebimento')
            ->willReturn($usuarioEntity);

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getUsuarioRecebimento')
            ->willReturn($usuarioDto);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testTramitacaoNaoRecebida(): void
    {
        $dataHoraRecebimento = new DateTime();

        $this->tramitacaoEntity->expects(self::exactly(2))
            ->method('getDataHoraRecebimento')
            ->willReturn($dataHoraRecebimento);

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getDataHoraRecebimento')
            ->willReturn($dataHoraRecebimento);

        $usuarioDto = $this->createMock(Usuario::class);
        $usuarioEntity = $this->createMock(Usuario::class);

        $usuarioDto->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->tramitacaoEntity->expects(self::exactly(2))
            ->method('getUsuarioRecebimento')
            ->willReturn($usuarioEntity);

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getUsuarioRecebimento')
            ->willReturn($usuarioDto);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }
}
