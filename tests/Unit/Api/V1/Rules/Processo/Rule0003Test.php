<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Processo/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Processo\Rule0003;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|ModalidadeMeio $modalidadeMeioDto;

    private MockObject|ModalidadeMeio $modalidadeMeioEntity;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|ProcessoRepository $processoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->modalidadeMeioDto = $this->createMock(ModalidadeMeio::class);
        $this->modalidadeMeioEntity = $this->createMock(ModalidadeMeio::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->processoRepository = $this->createMock(ProcessoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->processoRepository,
            $this->parameterBag
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testComComponenteDigital(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('FÍSICO', 'ELETRÔNICO');

        $this->modalidadeMeioDto->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeioEntity->expects(self::once())
            ->method('getValor')
            ->willReturn('FÍSICO');

        $this->processoDto->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioDto);

        $this->processoEntity->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioEntity);

        $this->processoRepository->expects(self::once())
            ->method('hasComponenteDigital')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testSemComponenteDigital(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('FÍSICO', 'ELETRÔNICO');

        $this->modalidadeMeioDto->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeioEntity->expects(self::once())
            ->method('getValor')
            ->willReturn('FÍSICO');

        $this->processoDto->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioDto);

        $this->processoEntity->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioEntity);

        $this->processoRepository->expects(self::once())
            ->method('hasComponenteDigital')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->processoDto, $this->processoEntity, 'transaction');
    }
}
