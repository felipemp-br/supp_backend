<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assinatura/Rule0009Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura\Rule0009;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0009Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009Test extends TestCase
{
    private MockObject|AssinaturaEntity $assinaturaEntity;

    private MockObject|ComponenteDigital $componenteDigital;

    private MockObject|Documento $documento;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assinaturaEntity = $this->createMock(AssinaturaEntity::class);
        $this->componenteDigital = $this->createMock(ComponenteDigital::class);
        $this->documento = $this->createMock(Documento::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0009(
            $this->rulesTranslate
        );
    }

    public function testDocumentoJuntado(): void
    {
        $this->documento->expects(self::once())
            ->method('getjuntadaAtual')
            ->willReturn(new Juntada());

        $this->componenteDigital->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->assinaturaEntity->expects(self::once())
            ->method('getComponenteDigital')
            ->willReturn($this->componenteDigital);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->assinaturaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDocumentoNaoJuntado(): void
    {
        $this->documento->expects(self::once())
            ->method('getjuntadaAtual')
            ->willReturn(null);

        $this->componenteDigital->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->assinaturaEntity->expects(self::once())
            ->method('getComponenteDigital')
            ->willReturn($this->componenteDigital);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->assinaturaEntity, 'transaction'));
    }
}
