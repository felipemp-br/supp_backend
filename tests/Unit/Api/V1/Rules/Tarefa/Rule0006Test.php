<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Tests\Fixtures\SerializableAclInterface;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|AclProviderInterface $aclProvider;

    private MockObject|Classificacao $classificacao;

    private MockObject|EspecieSetor $especieSetor;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setorResponsavel;

    private MockObject|Setor $unidadeResponsavel;

    private MockObject|TarefaDto $tarefaDto;

    private MockObject|TarefaEntity $tarefaEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->aclProvider = $this->createMock(AclProviderInterface::class);
        $this->classificacao = $this->createMock(Classificacao::class);
        $this->especieSetor = $this->createMock(EspecieSetor::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorResponsavel = $this->createMock(Setor::class);
        $this->tarefaDto = $this->createMock(TarefaDto::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);
        $this->unidadeResponsavel = $this->createMock(Setor::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->aclProvider,
            $this->parameterBag
        );
    }

    public function testUnidadeApenasProtocolo(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('PROTOCOLO', 'ARQUIVO');

        $acl = $this->createMock(SerializableAclInterface::class);
        $acl->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $this->aclProvider->expects(self::exactly(2))
            ->method('findAcl')
            ->willReturn($acl);

        $this->classificacao->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->especieSetor->expects(self::exactly(2))
            ->method('getNome')
            ->willReturn('SERVIÇO DE CONFORMIDADE DOCUMENTAL');

        $this->unidadeResponsavel->expects(self::once())
            ->method('getApenasProtocolo')
            ->willReturn(true);

        $this->unidadeResponsavel->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $unidadeOrigem = $this->createMock(Setor::class);
        $unidadeOrigem->expects(self::once())
            ->method('getId')
            ->willReturn(3);

        $setorOrigem = $this->createMock(Setor::class);
        $setorOrigem->expects(self::once())
            ->method('getUnidade')
            ->willReturn($unidadeOrigem);

        $this->setorResponsavel->expects(self::exactly(2))
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $this->setorResponsavel->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($this->unidadeResponsavel);

        $this->tarefaDto->expects(self::exactly(2))
            ->method('getSetorOrigem')
            ->willReturn($setorOrigem);

        $this->tarefaDto->expects(self::exactly(4))
            ->method('getSetorResponsavel')
            ->willReturn($this->setorResponsavel);

        $this->tarefaDto->expects(self::exactly(5))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUnidadeSemProtocolo(): void
    {
        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('PROTOCOLO');

        $acl = $this->createMock(SerializableAclInterface::class);
        $acl->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $this->aclProvider->expects(self::exactly(2))
            ->method('findAcl')
            ->willReturn($acl);

        $this->classificacao->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->especieSetor->expects(self::once())
            ->method('getNome')
            ->willReturn('SERVIÇO DE CONFORMIDADE DOCUMENTAL');

        $this->unidadeResponsavel->expects(self::once())
            ->method('getApenasProtocolo')
            ->willReturn(false);

        $this->setorResponsavel->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $this->setorResponsavel->expects(self::once())
            ->method('getUnidade')
            ->willReturn($this->unidadeResponsavel);

        $this->tarefaDto->expects(self::exactly(2))
            ->method('getSetorResponsavel')
            ->willReturn($this->setorResponsavel);

        $this->tarefaDto->expects(self::exactly(5))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }
}
