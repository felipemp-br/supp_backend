<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|DesentranhamentoDto $desentranhamentoDto;

    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|Documento $documento;

    private MockObject|Juntada $juntada;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoDocumentoRepository $vinculacaoDocumentoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->desentranhamentoDto = $this->createMock(DesentranhamentoDto::class);
        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->documento = $this->createMock(Documento::class);
        $this->juntada = $this->createMock(Juntada::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoDocumentoRepository = $this->createMock(VinculacaoDocumentoRepository::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->vinculacaoDocumentoRepository
        );
    }

    public function testDocumentoVinculado(): void
    {
        $vinculacaoDocumento = $this->createMock(VinculacaoDocumento::class);
        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn($vinculacaoDocumento);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->juntada->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getJuntada')
            ->willReturn($this->juntada);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoNaoVinculado(): void
    {
        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn(false);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->juntada->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getJuntada')
            ->willReturn($this->juntada);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction')
        );
    }
}
