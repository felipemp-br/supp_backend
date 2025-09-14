<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Dossie/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Dossie;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Dossie\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Dossie;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|DossieDto $dossieDto;

    private MockObject|DossieEntity $dossieEntity;

    private MockObject|PessoaResource $pessoaResource;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dossieDto = $this->createMock(DossieDto::class);
        $this->dossieEntity = $this->createMock(DossieEntity::class);
        $this->pessoaResource = $this->createMock(PessoaResource::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->pessoaResource
        );
    }

    public function testDocumentoPrincipalDiferente(): void
    {
        $pessoa = $this->createMock(Pessoa::class);
        $pessoa->expects(self::once())
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678902');

        $this->dossieDto->expects(self::exactly(2))
            ->method('getPessoa')
            ->willReturn($pessoa);

        $this->dossieDto->expects(self::exactly(2))
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678901');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoPrincipalIgual(): void
    {
        $pessoa = $this->createMock(Pessoa::class);
        $pessoa->expects(self::once())
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678901');

        $this->dossieDto->expects(self::exactly(3))
            ->method('getPessoa')
            ->willReturn($pessoa);

        $this->dossieDto->expects(self::exactly(4))
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678901');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction'));
    }

    public function testSemDocumentoPrincipalESemPessoa(): void
    {
        $this->dossieDto->expects(self::exactly(2))
            ->method('getPessoa')
            ->willReturn(null);

        $this->dossieDto->expects(self::once())
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction');
    }

    public function testComDocumentoPrincipalSemPessoa(): void
    {
        $this->dossieDto->expects(self::exactly(2))
            ->method('getPessoa')
            ->willReturn(null);

        $this->dossieDto->expects(self::exactly(3))
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678901');

        $this->pessoaResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComDocumentoPrincipalLocalizandoPessoa(): void
    {
        $this->dossieDto->expects(self::exactly(2))
            ->method('getPessoa')
            ->willReturn(null);

        $this->dossieDto->expects(self::exactly(3))
            ->method('getNumeroDocumentoPrincipal')
            ->willReturn('12345678901');

        $this->pessoaResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->createMock(Pessoa::class));

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction'));
    }
}
