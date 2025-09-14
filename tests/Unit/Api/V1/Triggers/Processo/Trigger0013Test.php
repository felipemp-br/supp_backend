<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Processo/Trigger0013Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Processo;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Trigger0013;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ClassificacaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0013Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0013Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Classificacao $classificacao;

    private MockObject|ClassificacaoRepository $classificacaoRepository;

    private MockObject|ClassificacaoResource $classificacaoResource;

    private MockObject|EspecieProcesso $especieProcesso;

    private MockObject|EspecieProcessoResource $especieProcessoResource;

    private MockObject|ModalidadeMeio $modalidadeMeio;

    private MockObject|ModalidadeMeioResource $modalidadeMeioResource;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->classificacao = $this->createMock(Classificacao::class);
        $this->classificacaoRepository = $this->createMock(ClassificacaoRepository::class);
        $this->classificacaoResource = $this->createMock(ClassificacaoResource::class);
        $this->especieProcesso = $this->createMock(EspecieProcesso::class);
        $this->especieProcessoResource = $this->createMock(EspecieProcessoResource::class);
        $this->modalidadeMeio = $this->createMock(ModalidadeMeio::class);
        $this->modalidadeMeioResource = $this->createMock(ModalidadeMeioResource::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);

        $this->trigger = new Trigger0013(
            $this->authorizationChecker,
            $this->classificacaoResource,
            $this->especieProcessoResource,
            $this->modalidadeMeioResource,
            $this->parameterBag
        );
    }

    /**
     * @throws Exception
     */
    public function testComRoleUsuarioExterno(): void
    {
        $this->parameterBag->expects(self::exactly(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls('305', 'COMUM', 'ELETRÔNICO');

        $this->processoDto->expects(self::once())
            ->method('getProtocoloEletronico')
            ->willReturn(true);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->classificacaoRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->classificacao);

        $this->classificacaoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->classificacaoRepository);

        $this->processoDto->expects(self::once())
            ->method('setClassificacao')
            ->with(self::identicalTo($this->classificacao));

        $this->especieProcessoResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->especieProcesso);

        $this->processoDto->expects(self::once())
            ->method('setEspecieProcesso')
            ->with(self::identicalTo($this->especieProcesso));

        $this->modalidadeMeioResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->modalidadeMeio);

        $this->processoDto->expects(self::once())
            ->method('setModalidadeMeio')
            ->with(self::identicalTo($this->modalidadeMeio));

        $this->processoDto->expects(self::once())
            ->method('getRequerimento')
            ->willReturn('Requerimento');

        $this->processoDto->expects(self::once())
            ->method('setVisibilidadeExterna')
            ->with(self::identicalTo(false));

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws Exception
     */
    public function testSemRoleUsuarioExterno(): void
    {
        $this->parameterBag->expects(self::never())
            ->method('get')
            ->willReturnOnConsecutiveCalls('305', 'COMUM', 'ELETRÔNICO');

        $this->processoDto->expects(self::once())
            ->method('getProtocoloEletronico')
            ->willReturn(true);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->classificacaoRepository->expects(self::never())
            ->method('findOneBy')
            ->willReturn($this->classificacao);

        $this->classificacaoResource->expects(self::never())
            ->method('getRepository')
            ->willReturn($this->classificacaoRepository);

        $this->processoDto->expects(self::never())
            ->method('setClassificacao')
            ->with(self::identicalTo($this->classificacao));

        $this->especieProcessoResource->expects(self::never())
            ->method('findOneBy')
            ->willReturn($this->especieProcesso);

        $this->processoDto->expects(self::never())
            ->method('setEspecieProcesso')
            ->with(self::identicalTo($this->especieProcesso));

        $this->modalidadeMeioResource->expects(self::never())
            ->method('findOneBy')
            ->willReturn($this->modalidadeMeio);

        $this->processoDto->expects(self::never())
            ->method('setModalidadeMeio')
            ->with(self::identicalTo($this->modalidadeMeio));

        $this->processoDto->expects(self::never())
            ->method('getRequerimento')
            ->willReturn('Requerimento');

        $this->processoDto->expects(self::never())
            ->method('setVisibilidadeExterna')
            ->with(self::identicalTo(false));

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }
}
