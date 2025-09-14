<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Chat/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Chat\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ChatDto $chatDto;

    private MockObject|ChatEntity $chatEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatDto = $this->createMock(ChatDto::class);
        $this->chatEntity = $this->createMock(ChatEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
        );
    }

    /**
     * @throws RuleException
     */
    public function testMesmoGrupo(): void
    {
        $this->chatDto->expects(self::once())
            ->method('getGrupo')
            ->willReturn(false);

        $this->chatEntity->expects(self::once())
            ->method('getGrupo')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->chatDto, $this->chatEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testGrupoDiferente(): void
    {
        $this->chatDto->expects(self::once())
            ->method('getGrupo')
            ->willReturn(false);

        $this->chatEntity->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatDto, $this->chatEntity, 'transaction');
    }
}
