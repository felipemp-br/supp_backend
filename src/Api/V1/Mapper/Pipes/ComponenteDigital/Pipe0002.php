<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/ComponenteDigital/Pipe0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\ComponenteDigital;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoAssinaturaExternaRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0002.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0002 implements PipeInterface
{
    /**
     * Pipe0002 constructor.
     */
    public function __construct(
        protected AuthorizationCheckerInterface $authorizationChecker,
        protected RequestStack $requestStack,
        protected TokenStorageInterface $tokenStorage,
        protected VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        protected VinculacaoDocumentoAssinaturaExternaRepository $vinculacaoDocumentoAssinaturaExternaRepository,
        protected ComponenteDigitalRepository $componenteDigitalRepository
    ) { }

    public function supports(): array
    {
        return [
            ComponenteDigitalDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ComponenteDigitalDTO|RestDtoInterface|null $restDto
     * @param ComponenteDigitalEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        // não tem request
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        // documento sem ligação com processo ou outro documento e componente digital relacionado ao perfil do usuário
        if (preg_match('/^IMAGEM_PERFIL.JPEG/', $restDto->getFileName()) ||
            preg_match('/^IMAGEM_CHANCELA.JPEG/', $restDto->getFileName())) {
            return;
        }

        // não tem direito de ver o processo
        if ($entity->getDocumento()?->getJuntadaAtual() &&
            $entity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso() &&
            (false === $this->authorizationChecker->isGranted(
                'VIEW',
                $entity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()
            )) ||
            ($entity->getDocumento()?->getJuntadaAtual()?->getVolume()->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted(
                    'VIEW',
                    $entity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()->getClassificacao()
                )))
        ) {
            $restDto = new ComponenteDigitalDTO();
            $restDto->setId($entity->getId());
            return;
        }

        // não tem direito de ver o documento
        if (false === $this->authorizationChecker->isGranted(
            'VIEW',
            $entity->getDocumento()
        )) {
            $restDto = new ComponenteDigitalDTO();
            $restDto->setId($entity->getId());
            return;
        }

        // é usuário interno
        if ($this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            return;
        }

        // é usuário externo, mas o processo tem visibilidade externa
        if ($entity->getDocumento()->getJuntadaAtual() &&
            $entity->getDocumento()->getJuntadaAtual()
                ->getVolume()->getProcesso()->getVisibilidadeExterna()) {
            return;
        }

        // é usuário externo, mas o processo foi criado por ele no protocolo eletrônico
        if ($entity->getDocumento()->getJuntadaAtual() &&
            $this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            ($entity->getDocumento()->getJuntadaAtual()
                    ->getVolume()->getProcesso()->getCriadoPor()?->getId() ===
                $this->tokenStorage->getToken()->getUser()->getId())) {
            return;
        }

        // é usuário externo, mas é interessado no processo
        if ($entity->getDocumento()->getJuntadaAtual() &&
            $this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            foreach ($entity->getDocumento()->getJuntadaAtual()
                         ->getVolume()->getProcesso()->getInteressados() as $interessado) {
                if ($interessado->getPessoa()->getNumeroDocumentoPrincipal() ===
                    $this->tokenStorage->getToken()->getUserIdentifier()) {
                    return;
                }
            }
        }

        // está vinculado a um ofício recebido por um usuário externo
        if ($entity->getDocumentoAvulsoOrigem()) {
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
                ->findOneBy([
                    'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                    'pessoa' => $entity->getDocumentoAvulsoOrigem()->getPessoaDestino(),
                ]);

            if ($vinculacaoUsuario &&
                ($vinculacaoUsuario->getPessoa()->getId() ===
                    $entity->getDocumentoAvulsoOrigem()->getPessoaDestino()->getId())) {
                return;
            }
        }

        // é usuário externo e tem chave de acesso
        if ($entity->getDocumento()->getJuntadaAtual() &&
            (null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->chaveAcesso) &&
                ($context->chaveAcesso
                    === $entity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()->getChaveAcesso())) {
                return;
            }
        }

        // é usuário externo e está fazendo o download de um processo com visibilidade externa
        if ($entity->getProcessoOrigem()?->getVisibilidadeExterna()) {
            return;
        }

        //é usuário externo e criou o componente digital
        if ($entity->getCriadoPor()?->getId() ===
            $this->tokenStorage->getToken()?->getUser()?->getId()) {
            return;
        }

        // se está assinado digitalmente, deixa acessar
        if ($entity->getAssinaturas()->count()) {
            return;
        }

        //é usuario externo e recebeu solicitacao de assinatura
        if ($this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')) {
            $assinaturaExterna = $this->vinculacaoDocumentoAssinaturaExternaRepository->findByDocumentoNumeroDocumentoPrincipal($entity->getDocumento()->getId(), $this->tokenStorage->getToken()->getUser()->getUsername());
            if($assinaturaExterna) {
                if(new DateTime() < $assinaturaExterna->getExpiraEm()) {
                    return;
                }
                if($this->componenteDigitalRepository->isAssinadoUser($entity->getId(), $this->tokenStorage->getToken()->getUser()->getId())) {
                    return;
                }
            }
        }

        $restDto = new ComponenteDigitalDTO();
        $restDto->setId($entity->getId());
    }

    public function getOrder(): int
    {
        return 2;
    }
}
