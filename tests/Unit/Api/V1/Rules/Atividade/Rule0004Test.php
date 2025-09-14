<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Atividade/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Atividade;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Atividade;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|AtividadeDto $atividadeDto;

    private MockObject|AtividadeEntity $atividadeEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->atividadeDto = $this->createMock(AtividadeDto::class);
        $this->atividadeEntity = $this->createMock(AtividadeEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate
        );
    }

    /**
     * @throws RuleException
     */
    public function testTarefaNaoEncerrada(): void
    {
        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testEncerrarTarefaComOficioRemitido(): void
    {
        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $documentoAvulso = $this->createMock(DocumentoAvulso::class);
        $documentoAvulso->expects(self::once())
            ->method('getDataHoraRemessa')
            ->willReturn(new DateTime());

        $documento = $this->createMock(Documento::class);
        $documento->expects(self::any())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn($documentoAvulso);

        $minutas = new ArrayCollection();
        $minutas->add($documento);

        $tarefa = $this->createMock(Tarefa::class);
        $tarefa->expects(self::once())
            ->method('getMinutas')
            ->willReturn($minutas);

        $this->atividadeDto->expects(self::once())
            ->method('getTarefa')
            ->willReturn($tarefa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    public function testEncerrarTarefaComOficioNaoRemitido(): void
    {
        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $documentoAvulso = $this->createMock(DocumentoAvulso::class);
        $documentoAvulso->expects(self::once())
            ->method('getDataHoraRemessa')
            ->willReturn(null);

        $documento = $this->createMock(Documento::class);
        $documento->expects(self::any())
            ->method('getDocumentoAvulsoRemessa')
            ->willReturn($documentoAvulso);

        $minutas = new ArrayCollection();
        $minutas->add($documento);

        $tarefa = $this->createMock(Tarefa::class);
        $tarefa->expects(self::once())
            ->method('getMinutas')
            ->willReturn($minutas);

        $this->atividadeDto->expects(self::once())
            ->method('getTarefa')
            ->willReturn($tarefa);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction');
    }
}
