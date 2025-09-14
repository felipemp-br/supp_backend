<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoAssinaturaExternaRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
 
    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        protected AuthorizationCheckerInterface $authorizationChecker,
        protected RequestStack $requestStack,
        protected TokenStorageInterface $tokenStorage,
        protected VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        protected VinculacaoDocumentoAssinaturaExternaRepository $vinculacaoDocumentoAssinaturaExternaRepository,
        protected DocumentoRepository $documentoRepository
    ) { }

    public function supports(): array
    {
        return [
            DocumentoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DocumentoDTO|RestDtoInterface|null $restDto
     * @param DocumentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        // não tem request
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        // não tem direito de ver o processo
        if ($entity->getJuntadaAtual() &&
            (false === $this->authorizationChecker->isGranted(
                'VIEW',
                $entity->getJuntadaAtual()->getVolume()->getProcesso()
            )) ||
            ($entity->getJuntadaAtual()?->getVolume()->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted(
                    'VIEW',
                    $entity->getJuntadaAtual()->getVolume()->getProcesso()->getClassificacao()
                )))
        ) {
            $restDto = new DocumentoDTO();
            $restDto->setId($entity->getId());

            return;
        }

        // não tem direito de ver o documento
        if (false === $this->authorizationChecker->isGranted(
            'VIEW',
            $entity
        )) {
            $restDto = new DocumentoDTO();
            $restDto->setId($entity->getId());

            return;
        }

        // é usuário interno
        if ($this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            return;
        }

        // é usuário externo, mas o processo tem visibilidade externa
        if ($entity->getJuntadaAtual() &&
            $entity->getJuntadaAtual()->getVolume()->getProcesso()->getVisibilidadeExterna()) {
            return;
        }

        // é usuário externo, mas o processo foi criado por ele no protocolo eletrônico
        if ($entity->getJuntadaAtual() &&
            $this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            ($entity->getJuntadaAtual()
                    ->getVolume()->getProcesso()->getCriadoPor()?->getId() ===
                $this->tokenStorage->getToken()->getUser()->getId())) {
            return;
        }

        // é usuário externo, mas é interessado no processo
        if ($entity->getJuntadaAtual() &&
            $this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            foreach ($entity->getJuntadaAtual()
                         ->getVolume()->getProcesso()->getInteressados() as $interessado) {
                if ($interessado->getPessoa()->getNumeroDocumentoPrincipal() ===
                    $this->tokenStorage->getToken()->getUserIdentifier()) {
                    return;
                }
            }
        }

        // está vinculado a um ofício recebido por um usuário externo
        if ($entity->getDocumentoAvulsoRemessa()) {
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
                ->findOneBy([
                    'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                    'pessoa' => $entity->getDocumentoAvulsoRemessa()->getPessoaDestino(),
                ]);

            if ($vinculacaoUsuario &&
                ($vinculacaoUsuario->getPessoa()->getId() ===
                    $entity->getDocumentoAvulsoRemessa()->getPessoaDestino()->getId())) {
                return;
            }
        }

        // é usuário externo e tem chave de acesso
        if ($entity->getJuntadaAtual() &&
            (null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->chaveAcesso) &&
                (null !== $context->chaveAcesso) &&
                ($context->chaveAcesso
                    === $entity->getJuntadaAtual()->getVolume()->getProcesso()->getChaveAcesso())) {
                return;
            }
        }

        //é usuário externo e criou o documento
        if ($entity->getCriadoPor()?->getId() ===
            $this->tokenStorage->getToken()?->getUser()?->getId()) {
            return;
        }

        //é usuario externo e recebeu solicitacao de assinatura
        if ($this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')) {
            $assinaturaExterna = $this->vinculacaoDocumentoAssinaturaExternaRepository->findByDocumentoNumeroDocumentoPrincipal($entity->getId(), $this->tokenStorage->getToken()->getUser()->getUsername());
            if($assinaturaExterna) {
                if($assinaturaExterna->getExpiraEm() >= new DateTime()) {
                    return;
                }
                if($this->documentoRepository->isAssinadoUser($entity->getId(), $this->tokenStorage->getToken()->getUser()->getId())) {
                    return;
                }
            }
        }

        if (!$restDto->getAssinado()) {
            $restDto = new DocumentoDTO();
            $restDto->setId($entity->getId());
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
