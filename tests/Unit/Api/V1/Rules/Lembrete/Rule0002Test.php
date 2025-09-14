<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lembrete/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lembrete;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lembrete\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Lembrete as LembreteEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lembrete;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|LembreteEntity $lembreteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $criadoPor;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->criadoPor = $this->createMock(Usuario::class);
        $this->lembreteEntity = $this->createMock(LembreteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    public function testLembreteNaoPertenceUsuario(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuario);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->lembreteEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($this->criadoPor);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->lembreteEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testLembretePertenceUsuario(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuario);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->lembreteEntity->expects(self::exactly(2))
            ->method('getCriadoPor')
            ->willReturn($this->criadoPor);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->lembreteEntity, 'transaction'));
    }
}
