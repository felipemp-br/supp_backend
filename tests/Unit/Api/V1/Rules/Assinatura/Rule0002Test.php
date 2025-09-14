<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assinatura/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>.
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AssinaturaDto $assinaturaDto;

    private MockObject|AssinaturaEntity $assinaturaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assinaturaDto = $this->createMock(AssinaturaDto::class);
        $this->assinaturaEntity = $this->createMock(AssinaturaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testSemToken(): void
    {
        $this->assinaturaDto->expects(self::any())
            ->method('getCadeiaCertificadoPEM')
            ->willReturn(null);

        $this->assinaturaDto->expects(self::any())
            ->method('getAssinatura')
            ->willReturn('');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComToken(): void
    {
        $this->assinaturaDto->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn('certificado');

        $this->assinaturaDto->expects(self::exactly(2))
            ->method('getAssinatura')
            ->willReturn('Assinatura');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction'));
    }
}
