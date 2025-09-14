<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoDocumento/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|Documento $documento;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoDocumentoDto $vinculacaoDocumentoDto;

    private MockObject|VinculacaoDocumentoEntity $vinculacaoDocumentoEntity;

    private MockObject|VinculacaoDocumentoRepository $vinculacaoDocumentoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documento = $this->createMock(Documento::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoDocumentoDto = $this->createMock(VinculacaoDocumentoDto::class);
        $this->vinculacaoDocumentoEntity = $this->createMock(VinculacaoDocumentoEntity::class);
        $this->vinculacaoDocumentoRepository = $this->createMock(VinculacaoDocumentoRepository::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->vinculacaoDocumentoRepository
        );
    }

    public function testDocumentoPrincipalVinculado(): void
    {
        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn(true);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

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
    public function testDocumentoPrincipalNaoVinculado(): void
    {
        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn(false);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoDocumentoDto, $this->vinculacaoDocumentoEntity, 'transaction')
        );
    }
}
