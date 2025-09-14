<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Contato/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Contato;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Contato as ContatoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Contato\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Contato as ContatoEntity;
use SuppCore\AdministrativoBackend\Entity\GrupoContato;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\TipoContato;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\ContatoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Contato;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|ContatoDto $contatoDto;

    private MockObject|ContatoEntity $contatoEntity;

    private MockObject|ContatoRepository $contatoRepository;

    private MockObject|GrupoContato $grupoContato;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setor;

    private MockObject|Setor $unidade;

    private MockObject|TipoContato $tipoContato;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->contatoDto = $this->createMock(ContatoDto::class);
        $this->contatoEntity = $this->createMock(ContatoEntity::class);
        $this->contatoRepository = $this->createMock(ContatoRepository::class);
        $this->grupoContato = $this->createMock(GrupoContato::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setor = $this->createMock(Setor::class);
        $this->tipoContato = $this->createMock(TipoContato::class);
        $this->unidade = $this->createMock(Setor::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->contatoRepository
        );
    }

    /**
     * @throws RuleException
     */
    public function testContatoCadastrado(): void
    {
        $this->contatoDto->expects(self::once())
            ->method('getGrupoContato')
            ->willReturn($this->grupoContato);

        $this->contatoDto->expects(self::once())
            ->method('getTipoContato')
            ->willReturn($this->tipoContato);

        $this->contatoDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn($this->unidade);

        $this->contatoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->contatoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->contatoRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn([[]]);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->contatoDto, $this->contatoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testContatoNaoCadastrado(): void
    {
        $this->contatoDto->expects(self::once())
            ->method('getGrupoContato')
            ->willReturn($this->grupoContato);

        $this->contatoDto->expects(self::once())
            ->method('getTipoContato')
            ->willReturn($this->tipoContato);

        $this->contatoDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn($this->unidade);

        $this->contatoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->contatoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->contatoRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->contatoDto, $this->contatoEntity, 'transaction'));
    }
}
