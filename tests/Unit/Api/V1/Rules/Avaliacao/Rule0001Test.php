<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Avaliacao/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Avaliacao;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao as AvaliacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Avaliacao\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Avaliacao as AvaliacaoEntity;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\AvaliacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Avaliacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AvaliacaoDto $avaliacaoDto;

    private MockObject|AvaliacaoEntity $avaliacaoEntity;

    private MockObject|AvaliacaoRepository $avaliacaoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->avaliacaoDto = $this->createMock(AvaliacaoDto::class);
        $this->avaliacaoEntity = $this->createMock(AvaliacaoEntity::class);
        $this->avaliacaoRepository = $this->createMock(AvaliacaoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0001(
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->avaliacaoRepository
        );
    }

    public function testTokenSemUsuario(): void
    {
        $this->token->expects(self::once())
            ->method('getuser')
            ->willReturn(null);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->avaliacaoDto, $this->avaliacaoEntity, 'transaction');
    }

    public function testCriarAvaliacaoNaoPermitida(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);

        $this->token->expects(self::once())
            ->method('getuser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->avaliacaoDto->expects(self::once())
            ->method('getObjetoAvaliado')
            ->willReturn($this->createMock(ObjetoAvaliado::class));

        $avaliacoes = [];

        $avaliacoes[0] = $this->createMock(AvaliacaoEntity::class);

        $avaliacoes[0]->expects(self::any())
            ->method('getCriadoEm')
            ->willReturn(new DateTime('-100 days'));

        $avaliacoes[1] = $this->createMock(AvaliacaoEntity::class);

        $avaliacoes[1]->expects(self::any())
            ->method('getCriadoEm')
            ->willReturn(new DateTime('-20 days'));

        $this->avaliacaoRepository->expects(self::once())
            ->method('findBy')
            ->willReturn($avaliacoes);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->avaliacaoDto, $this->avaliacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testCriarAvaliacaoPermitida(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);

        $this->token->expects(self::once())
            ->method('getuser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->avaliacaoDto->expects(self::once())
            ->method('getObjetoAvaliado')
            ->willReturn($this->createMock(ObjetoAvaliado::class));

        $avaliacoes = [];

        $avaliacoes[0] = $this->createMock(AvaliacaoEntity::class);

        $avaliacoes[0]->expects(self::any())
            ->method('getCriadoEm')
            ->willReturn(new DateTime('-100 days'));

        $avaliacoes[1] = $this->createMock(AvaliacaoEntity::class);

        $avaliacoes[1]->expects(self::any())
            ->method('getCriadoEm')
            ->willReturn(new DateTime('-50 days'));

        $this->avaliacaoRepository->expects(self::once())
            ->method('findBy')
            ->willReturn($avaliacoes);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->avaliacaoDto, $this->avaliacaoEntity, 'transaction'));
    }
}
