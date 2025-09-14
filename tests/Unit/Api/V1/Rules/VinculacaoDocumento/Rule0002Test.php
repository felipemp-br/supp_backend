<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoDocumento/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoDocumento;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento as VinculacaoDocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoDocumento\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|Documento $documento;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoDocumentoDto $vinculacaoDocumentoDto;

    private MockObject|VinculacaoDocumentoEntity $vinculacaoDocumentoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documento = $this->createMock(Documento::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoDocumentoDto = $this->createMock(VinculacaoDocumentoDto::class);
        $this->vinculacaoDocumentoEntity = $this->createMock(VinculacaoDocumentoEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testMaiorIgualCemDocumentosVinculados(): void
    {
        $vinculacoesDocumento = new ArrayCollection();

        for ($i = 1; $i <= 100; ++$i) {
            $vinculacoesDocumento->add(new VinculacaoDocumentoEntity());
        }

        $this->documento->expects(self::once())
            ->method('getVinculacoesDocumentos')
            ->willReturn($vinculacoesDocumento);

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
    public function testMenorCemDocumentosVinculados(): void
    {
        $vinculacoesDocumento = new ArrayCollection();

        for ($i = 1; $i < 100; ++$i) {
            $vinculacoesDocumento->add(new VinculacaoDocumentoEntity());
        }

        $this->documento->expects(self::once())
            ->method('getVinculacoesDocumentos')
            ->willReturn($vinculacoesDocumento);

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
