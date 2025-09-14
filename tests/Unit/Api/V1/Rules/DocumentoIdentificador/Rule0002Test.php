<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/DocumentoIdentificador/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoIdentificador as DocumentoIdentificadorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoIdentificador\Rule0002;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador as DocumentoIdentificadorEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\DocumentoIdentificador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|DocumentoIdentificadorDto $documentoIdentificadorDto;

    private MockObject|DocumentoIdentificadorEntity $documentoIdentificadorEntity;

    private MockObject|Pessoa $pessoa;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->documentoIdentificadorDto = $this->createMock(DocumentoIdentificadorDto::class);
        $this->documentoIdentificadorEntity = $this->createMock(DocumentoIdentificadorEntity::class);
        $this->pessoa = $this->createMock(Pessoa::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testPessoaValidada(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(true);

        $this->documentoIdentificadorDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->documentoIdentificadorDto, $this->documentoIdentificadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testPessoaNaoValidada(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(false);

        $this->documentoIdentificadorDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->documentoIdentificadorDto, $this->documentoIdentificadorEntity, 'transaction')
        );
    }

    /**
     * @throws RuleException
     */
    public function testAdministrador(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->documentoIdentificadorDto, $this->documentoIdentificadorEntity, 'transaction')
        );
    }
}
