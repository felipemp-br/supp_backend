<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Assunto/Trigger0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Assunto;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto as AssuntoDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Assunto\Trigger0001;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Repository\AssuntoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Assunto;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001Test extends TestCase
{
    private MockObject|AssuntoDto $assuntoDto;

    private MockObject|AssuntoEntity $assuntoEntity;

    private MockObject|AssuntoRepository $assuntoRepository;

    private MockObject|AssuntoResource $assuntoResource;

    private MockObject|Processo $processo;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assuntoDto = $this->createMock(AssuntoDto::class);
        $this->assuntoEntity = $this->createMock(AssuntoEntity::class);
        $this->assuntoRepository = self::createMock(AssuntoRepository::class);
        $this->assuntoResource = $this->createMock(AssuntoResource::class);
        $this->processo = $this->createMock(Processo::class);

        $this->trigger = new Trigger0001(
            $this->assuntoResource
        );
    }

    /**
     * @throws Exception
     */
    public function testAssuntoPrincipalAutomatico(): void
    {
        $this->assuntoRepository->expects(self::once())
            ->method('findCountPrincipalByProcessoId')
            ->willReturn(0);

        $this->assuntoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->assuntoRepository);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(925747);

        $this->assuntoDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->assuntoDto->expects(self::once())
            ->method('setPrincipal')
            ->with(self::identicalTo(true));

        $this->trigger->execute($this->assuntoDto, $this->assuntoEntity, 'transaction');
    }

    /**
     * @throws Exception
     */
    public function testAssuntoPrincipalJaDefinido(): void
    {
        $this->assuntoRepository->expects(self::once())
            ->method('findCountPrincipalByProcessoId')
            ->willReturn(1);

        $this->assuntoResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->assuntoRepository);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(925747);

        $this->assuntoDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->assuntoDto->expects(self::never())
            ->method('setPrincipal')
            ->with(self::identicalTo(true));

        $this->trigger->execute($this->assuntoDto, $this->assuntoEntity, 'transaction');
    }
}
