<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Documento/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Documento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Documento\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Documento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|DocumentoDto $documentoDto;

    private MockObject|DocumentoEntity $documentoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|VinculacaoDocumentoRepository $vinculacaoDocumentoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documentoDto = $this->createMock(DocumentoDto::class);
        $this->documentoEntity = $this->createMock(DocumentoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->vinculacaoDocumentoRepository = $this->createMock(VinculacaoDocumentoRepository::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->vinculacaoDocumentoRepository,
            $this->transactionManager
        );
    }

    /**
     * @throws RuleException
     */
    public function testContextoConverteAnexoEmMinuta(): void
    {
        $context = $this->createMock(Context::class);
        $context->expects(self::once())
            ->method('getValue')
            ->willReturn(25);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->documentoDto->expects(self::once())
            ->method('getId')
            ->willReturn(25);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->documentoDto, $this->documentoEntity, 'transaction'));
    }

    public function testDocumentoVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn(true);

        $this->documentoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->documentoDto->expects(self::once())
            ->method('getTarefaOrigem')
            ->willReturn($this->tarefa);

        $this->documentoDto->expects(self::once())
            ->method('getId')
            ->willReturn(25);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->documentoDto, $this->documentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoNaoVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->vinculacaoDocumentoRepository->expects(self::once())
            ->method('findByDocumentoVinculado')
            ->willReturn(false);

        $this->documentoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->documentoDto->expects(self::once())
            ->method('getTarefaOrigem')
            ->willReturn($this->tarefa);

        $this->documentoDto->expects(self::once())
            ->method('getId')
            ->willReturn(25);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->documentoDto, $this->documentoEntity, 'transaction'));
    }
}
