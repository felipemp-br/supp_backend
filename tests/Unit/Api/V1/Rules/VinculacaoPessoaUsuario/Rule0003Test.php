<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoPessoaUsuario/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoPessoaUsuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoPessoaUsuario\Rule0003;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003Test.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoPessoaUsuarioDto $vinculacaoPessoaUsuarioDto;

    private MockObject|VinculacaoPessoaUsuarioEntity $vinculacaoPessoaUsuarioEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoPessoaUsuarioDto = $this->createMock(VinculacaoPessoaUsuarioDto::class);
        $this->vinculacaoPessoaUsuarioEntity = $this->createMock(VinculacaoPessoaUsuarioEntity::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderes(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoPessoaUsuarioDto, $this->vinculacaoPessoaUsuarioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoderes(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate(
                $this->vinculacaoPessoaUsuarioDto,
                $this->vinculacaoPessoaUsuarioEntity,
                'transaction'
            )
        );
    }
}
