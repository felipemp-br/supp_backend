<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/DocumentoIdentificador/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoIdentificador\Rule0004;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as DocumentoIdentificadorEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|DocumentoIdentificadorEntity $documentoIdentificadorEntity;

    private MockObject|Pessoa $pessoa;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documentoIdentificadorEntity = $this->createMock(DocumentoIdentificadorEntity::class);
        $this->pessoa = $this->createMock(Pessoa::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate
        );
    }

    public function testPessoaValidada(): void
    {
        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(true);

        $this->documentoIdentificadorEntity->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->documentoIdentificadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testPessoaNaoValidada(): void
    {
        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(false);

        $this->documentoIdentificadorEntity->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate(null, $this->documentoIdentificadorEntity, 'transaction')
        );
    }
}
