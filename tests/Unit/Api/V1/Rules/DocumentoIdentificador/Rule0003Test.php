<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/DocumentoIdentificador/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoIdentificador\Rule0003;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as DocumentoIdentificadorEntity;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|DocumentoIdentificadorEntity $documentoIdentificadorEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documentoIdentificadorEntity = $this->createMock(DocumentoIdentificadorEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate
        );
    }

    public function testDocumentoIdentificadorIntegracao(): void
    {
        $origemDados = $this->createMock(OrigemDados::class);
        $this->documentoIdentificadorEntity->expects(self::once())
            ->method('getOrigemDados')
            ->willReturn($origemDados);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->documentoIdentificadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoIdentificadorNaoIntegracao(): void
    {
        $this->documentoIdentificadorEntity->expects(self::once())
            ->method('getOrigemDados')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->documentoIdentificadorEntity, 'transaction'));
    }
}
