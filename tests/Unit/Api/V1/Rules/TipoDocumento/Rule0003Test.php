<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/TipoDocumento/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\TipoDocumento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\TipoDocumento\Rule0003;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento as TipoDocumentoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\TipoDocumento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;
    private MockObject|TipoDocumentoEntity $tipoDocumentoEntity;
    private RuleInterface $rule;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tipoDocumentoEntity = $this->createMock(TipoDocumentoEntity::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPermissao(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->tipoDocumentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPermissao(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tipoDocumentoEntity, 'transaction'));
    }
}
