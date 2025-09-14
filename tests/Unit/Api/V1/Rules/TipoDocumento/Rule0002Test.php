<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/TipoDocumento/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\TipoDocumento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\TipoDocumento\Rule0002;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento as TipoDocumentoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\TipoDocumento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TipoDocumentoEntity $tipoDocumentoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tipoDocumentoEntity = $this->createMock(TipoDocumentoEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testTipoDocumentoCertidao(): void
    {
        $this->tipoDocumentoEntity->expects(self::once())
            ->method('getNome')
            ->willReturn('CERTIDÃO');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->tipoDocumentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testTipoDocumentoNaoCertidao(): void
    {
        $this->tipoDocumentoEntity->expects(self::once())
            ->method('getNome')
            ->willReturn('CARTA');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tipoDocumentoEntity, 'transaction'));
    }
}
