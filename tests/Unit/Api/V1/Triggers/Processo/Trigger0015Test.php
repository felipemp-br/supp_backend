<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Processo;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Trigger0015;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Repository\FormularioRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\LoaderInterface;

/**
 * Class Trigger0015Test.
 */
class Trigger0015Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ComponenteDigitalResource $componenteDigitalResource;

    private MockObject|DadosFormularioResource $dadosFormularioResource;

    private MockObject|DocumentoResource $documentoResource;

    private MockObject|Environment $twig;

    private MockObject|FormularioResource $formularioResource;

    private MockObject|JuntadaResource $juntadaResource;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|TipoDocumentoResource $tipoDocumentoResource;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioToken;

    private MockObject|VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    private TriggerInterface $trigger;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->componenteDigitalResource = $this->createMock(ComponenteDigitalResource::class);
        $this->dadosFormularioResource = $this->createMock(DadosFormularioResource::class);
        $this->documentoResource = $this->createMock(DocumentoResource::class);
        $this->formularioResource = $this->createMock(FormularioResource::class);
        $this->juntadaResource = $this->createMock(JuntadaResource::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->tipoDocumentoResource = $this->createMock(TipoDocumentoResource::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->usuarioToken = $this->createMock(Usuario::class);
        $this->vinculacaoPessoaUsuarioResource = $this->createMock(VinculacaoPessoaUsuarioResource::class);

        $this->trigger = new Trigger0015(
            $this->authorizationChecker,
            $this->tokenStorage,
            $this->componenteDigitalResource,
            $this->documentoResource,
            $this->tipoDocumentoResource,
            $this->juntadaResource,
            $this->vinculacaoPessoaUsuarioResource,
            $this->parameterBag,
            $this->twig,
            $this->dadosFormularioResource,
            $this->formularioResource
        );
    }

    /**
     * @throws ORMException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws OptimisticLockException
     * @throws SyntaxError
     * @throws Exception
     */
    public function testCriarComponenteDigitalUsuarioConveniado(): void
    {
        $this->processoDto->expects(self::exactly(4))
            ->method('getDadosRequerimento')
            ->willReturn('{"tipoRequerimento":"teste"}');

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $pessoa = $this->createMock(Pessoa::class);
        $pessoa->expects(self::once())
            ->method('getNome')
            ->willReturn('NOME');

        $vinculacaoPessoaUsuario = $this->createMock(VinculacaoPessoaUsuario::class);
        $vinculacaoPessoaUsuario->expects(self::once())
            ->method('getPessoa')
            ->willReturn($pessoa);

        $vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);
        $vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findBy')
            ->willReturn([$vinculacaoPessoaUsuario]);

        $this->vinculacaoPessoaUsuarioResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($vinculacaoPessoaUsuarioRepository);

        $formulario = $this->createMock(Formulario::class);
        $formulario->expects(self::exactly(2))
            ->method('getTemplate')
            ->willReturn('template_01');

        $formularioRepository = $this->createMock(FormularioRepository::class);
        $formularioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($formulario);

        $this->formularioResource->expects(self::once())
            ->method('getRepository')
            ->willReturn($formularioRepository);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('REQUERIMENTO');

        $tipoDocumento = $this->createMock(TipoDocumento::class);

        $this->tipoDocumentoResource->expects(self::once())
            ->method('findOneBy')
            ->willReturn($tipoDocumento);

        $this->documentoResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(Documento::class), self::isType('string'));

        $this->componenteDigitalResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(ComponenteDigital::class), self::isType('string'));

        $this->processoDto->expects(self::once())
            ->method('getVolumes')
            ->willReturn([$this->createMock(Volume::class)]);

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects(self::once())
            ->method('exists')
            ->willReturn(true);

        $this->twig->expects(self::once())
            ->method('getLoader')
            ->willReturn($loader);

        $this->twig->expects(self::once())
            ->method('render')
            ->willReturn('test');

        $this->dadosFormularioResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(DadosFormulario::class), self::isType('string'));

        $this->juntadaResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(Juntada::class), self::isType('string'));

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws ORMException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws OptimisticLockException
     * @throws SyntaxError
     * @throws Exception
     */
    public function testRoleColaborador(): void
    {
        $this->processoDto->expects(self::once())
            ->method('getDadosRequerimento')
            ->willReturn('{"tipoRequerimento":"teste"}');

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->juntadaResource->expects(self::never())
            ->method('create')
            ->with(self::isInstanceOf(Juntada::class), self::isType('string'));

        $this->trigger->execute($this->processoDto, $this->processoEntity, 'transaction');
    }
}
