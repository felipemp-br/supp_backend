<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Chat/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Chat\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|ChatEntity $chatEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatEntity = $this->createMock(ChatEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
        );
    }

    /**
     * @throws RuleException
     */
    public function testChatEmGrupo(): void
    {
        $this->chatEntity->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->chatEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testChatSemGrupo(): void
    {
        $this->chatEntity->expects(self::once())
            ->method('getGrupo')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->chatEntity, 'transaction');
    }
}
