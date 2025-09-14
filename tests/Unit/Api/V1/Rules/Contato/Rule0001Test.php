<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Contato/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Contato;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Contato as ContatoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Contato\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Contato as ContatoEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Contato;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ContatoDto $contatoDto;

    private MockObject|ContatoEntity $contatoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->contatoDto = $this->createMock(ContatoDto::class);
        $this->contatoEntity = $this->createMock(ContatoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
        );
    }

    public function testSemContato(): void
    {
        $this->contatoDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->contatoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->contatoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->contatoDto, $this->contatoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testContatoUsuario(): void
    {
        $usuario = $this->createMock(Usuario::class);

        $this->contatoDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->contatoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->contatoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->contatoDto, $this->contatoEntity, 'transaction'));
    }
}
