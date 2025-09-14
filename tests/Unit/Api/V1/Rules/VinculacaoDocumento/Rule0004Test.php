<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoDocumento/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|Documento $documento;

    private MockObject|Documento $documentoVinculado;

    private MockObject|DocumentoAvulso $documentoAvulso;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoDocumentoDto $vinculacaoDocumentoDto;

    private MockObject|VinculacaoDocumentoEntity $vinculacaoDocumentoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documento = $this->createMock(Documento::class);
        $this->documentoAvulso = $this->createMock(DocumentoAvulso::class);
        $this->documentoVinculado = $this->createMock(Documento::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoDocumentoDto = $this->createMock(VinculacaoDocumentoDto::class);
        $this->vinculacaoDocumentoEntity = $this->createMock(VinculacaoDocumentoEntity::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate
        );
    }

    public function testVinculadaComunicacao(): void
    {
        $this->documento->expects(self::once())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn(null);

        $this->documentoVinculado->expects(self::once())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn($this->documentoAvulso);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumentoVinculado')
            ->willReturn($this->documentoVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoDocumentoDto, $this->vinculacaoDocumentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNaoVinculadaComunicacao(): void
    {
        $this->documento->expects(self::once())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn(null);

        $this->documentoVinculado->expects(self::once())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn(null);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->vinculacaoDocumentoDto->expects(self::once())
            ->method('getDocumentoVinculado')
            ->willReturn($this->documentoVinculado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoDocumentoDto, $this->vinculacaoDocumentoEntity, 'transaction')
        );
    }
}
