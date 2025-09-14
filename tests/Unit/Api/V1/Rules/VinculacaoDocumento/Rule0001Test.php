<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoDocumento/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Documento $documento;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoDocumentoDto $vinculacaoDocumentoDto;

    private MockObject|VinculacaoDocumentoEntity $vinculacaoDocumentoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->documento = $this->createMock(Documento::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoDocumentoDto = $this->createMock(VinculacaoDocumento::class);
        $this->vinculacaoDocumentoEntity = $this->createMock(VinculacaoDocumentoEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoder(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoDocumentoDto, $this->vinculacaoDocumentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoder(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $documentoVinculado = $this->createMock(Documento::class);
        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumentoVinculado')
            ->willReturn($documentoVinculado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoDocumentoDto, $this->vinculacaoDocumentoEntity, 'transaction')
        );
    }
}
