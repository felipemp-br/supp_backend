<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0015.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoSolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function strlen;

/**
 * Class Trigger0015.
 *
 * @descSwagger  =Cria componenete digital para usuário conveniado
 *
 * @classeSwagger=Trigger0015
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0015 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param AuthorizationCheckerInterface       $authorizationChecker
     * @param TokenStorageInterface               $tokenStorage
     * @param ComponenteDigitalResource           $componenteDigitalResource
     * @param DocumentoResource                   $documentoResource
     * @param TipoDocumentoResource               $tipoDocumentoResource
     * @param JuntadaResource                     $juntadaResource
     * @param VinculacaoPessoaUsuarioResource     $vinculacaoPessoaUsuarioResource
     * @param ParameterBagInterface               $parameterBag
     * @param Environment                         $twig
     * @param DadosFormularioResource             $dadosFormularioResource
     * @param FormularioResource                  $formularioResource
     * @param TipoSolicitacaoAutomatizadaResource $tipoSolicitacaoAutomatizadaResource
     * @param SolicitacaoAutomatizadaResource     $solicitacaoAutomatizadaResource
     * @param ProtocoloExternoManager             $protocoloExternoManager
     */
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly DocumentoResource $documentoResource,
        private readonly TipoDocumentoResource $tipoDocumentoResource,
        private readonly JuntadaResource $juntadaResource,
        private readonly VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Environment $twig,
        private readonly DadosFormularioResource $dadosFormularioResource,
        private readonly FormularioResource $formularioResource,
        private readonly TipoSolicitacaoAutomatizadaResource $tipoSolicitacaoAutomatizadaResource,
        private readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource,
        private readonly ProtocoloExternoManager $protocoloExternoManager,
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(
        ProcessoDTO|RestDtoInterface|null $restDto,
        ProcessoEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        if ($restDto->getProtocoloEletronico()
            && (
                $this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')
                || $this->authorizationChecker->isGranted('ROLE_DPU')
            )) {
            if (!$restDto->getRequerimento() && !$restDto->getDadosRequerimento()) {
                return;
            }

            $vinculacoesUsuario = $this
                ->vinculacaoPessoaUsuarioResource
                ->getRepository()
                ->findBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser()]);

            $vinculados = [];
            foreach ($vinculacoesUsuario as $vinc) {
                $vinculados[] = $vinc->getPessoa()->getNome();
            }

            $template = 'Resources/Processo/Requerimentos/requerimento_basico.html.twig';
            $dadosTemplate = [
                'processo' => $restDto,
                'usuario' => $this->tokenStorage->getToken()->getUser(),
                'vinculados' => $vinculados,
            ];

            $dadosFormulario = $restDto->getDadosRequerimento() ?
                json_decode($restDto->getDadosRequerimento(), true) :
                [];

            $siglaFormulario = $dadosFormulario['tipoRequerimento'] ?? false;
            if ($siglaFormulario) {
                $formularioEntity = $this->formularioResource->getRepository()->findOneBy(
                    ['sigla' => $siglaFormulario]
                );
                if ($formularioEntity && $this->twig->getLoader()->exists($formularioEntity->getTemplate())) {
                    $template = $formularioEntity->getTemplate();
                    $dadosTemplate += $dadosFormulario;
                }
            }

            $conteudoHTML = $this->twig->render(
                $template,
                $dadosTemplate
            );

            $tipoDocumento = $this
                ->tipoDocumentoResource
                ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_2')]);

            $documentoDTO = (new Documento())
                ->setTipoDocumento($tipoDocumento)
                ->setProcessoOrigem($entity);
            $documento = $this->documentoResource->create($documentoDTO, $transactionId);

            $componenteDigitalDTO = (new ComponenteDigital())
                ->setProcessoOrigem($entity)
                ->setMimetype('text/html')
                ->setExtensao('html')
                ->setEditavel(false)
                ->setNivelComposicao(3)
                ->setFileName('REQUERIMENTO.html')
                ->setConteudo($conteudoHTML)
                ->setHash(hash('SHA256', $conteudoHTML))
                ->setTamanho(strlen($conteudoHTML))
                ->setDocumento($documento);
            $componenteDigitalEntity = $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

            $volume = $restDto->getVolumes()[0];

            $juntadaDTO = new Juntada();
            $juntadaDTO->setDocumento($documento);
            $juntadaDTO->setVolume($volume);
            $juntadaDTO->setDescricao('DOCUMENTO RECEBIDO VIA PROTOCOLO EXTERNO');
            $this->juntadaResource->create($juntadaDTO, $transactionId);

            if ($dadosFormulario && isset($formularioEntity)) {
                $dadosFormularioDTO = (new DadosFormulario())
                    ->setComponenteDigital($componenteDigitalEntity)
                    ->setFormulario($formularioEntity)
                    ->setDataValue($restDto->getDadosRequerimento());
                $dadosFormularioEntity = $this->dadosFormularioResource->create($dadosFormularioDTO, $transactionId);

                $tipoSolicitacaoAutomatizada = $this->tipoSolicitacaoAutomatizadaResource->getRepository()->findOneBy(
                    ['formulario' => $formularioEntity]
                );
                if ($tipoSolicitacaoAutomatizada) {
                    $dadosProtocoloExterno = $this->protocoloExternoManager
                        ->getDadosProtocoloExterno($formularioEntity, $entity, $restDto);
                    $solicitacaoAutomatizadaDTO = (new SolicitacaoAutomatizada())
                        ->setProcesso($entity)
                        ->setTipoSolicitacaoAutomatizada($tipoSolicitacaoAutomatizada)
                        ->setDadosFormulario($dadosFormularioEntity)
                        ->setObservacao($tipoSolicitacaoAutomatizada->getDescricao().
                            ". Beneficiário(a): ".
                            $dadosProtocoloExterno->getRequerente()->getNome().
                            " (CPF ".$dadosProtocoloExterno->getRequerente()->getNumeroDocumentoPrincipal().").")
                        ->setBeneficiario($dadosProtocoloExterno->getRequerente());
                    $this->solicitacaoAutomatizadaResource->create($solicitacaoAutomatizadaDTO, $transactionId);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
