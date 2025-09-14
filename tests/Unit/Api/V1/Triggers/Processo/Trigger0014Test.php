<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Processo/Trigger0014Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Processo;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Trigger0014;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0014Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0014Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|EspecieTarefaResource $especieTarefaResource;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|TarefaResource $tarefaResource;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->especieTarefaResource = $this->createMock(EspecieTarefaResource::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->tarefaResource = $this->createMock(TarefaResource::class);

        $this->trigger = new Trigger0014(
            $this->authorizationChecker,
            $this->tarefaResource,
            $this->especieTarefaResource,
            $this->parameterBag
        );
    }

    /**
     * @throws Exception
     */
    public function testCriarTarefaProcessoUsuarioConveniado(): void
    {
        $this->processoDto->expects(self::once())
            ->method('getProtocoloEletronico')
            ->willReturn(true);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ANALISAR DEMANDAS');

        $this->especieTarefaResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->createMock(EspecieTarefa::class));

        $this->processoDto->expects(self::once())
            ->method('getSetorInicial')
            ->willReturn($this->createMock(Setor::class));

        $this->tarefaResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(Tarefa::class), 'transaction');

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws Exception
     */
    public function testCriarTarefaProcessoUsuarioColaborador(): void
    {
        $this->processoDto->expects(self::once())
            ->method('getProtocoloEletronico')
            ->willReturn(true);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->parameterBag->expects(self::never())
            ->method('get')
            ->willReturn('ANALISAR DEMANDAS');

        $this->especieTarefaResource->expects(self::never())
            ->method('findOneBy')
            ->willReturn($this->createMock(EspecieTarefa::class));

        $this->processoDto->expects(self::never())
            ->method('getSetorInicial')
            ->willReturn($this->createMock(Setor::class));

        $this->tarefaResource->expects(self::never())
            ->method('create')
            ->with(self::isInstanceOf(Tarefa::class), 'transaction');

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }
}
