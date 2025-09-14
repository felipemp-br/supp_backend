<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Juntada/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
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
    protected AuthorizationCheckerInterface $authorizationChecker;

    protected RequestStack $requestStack;

    protected TokenStorageInterface $tokenStorage;

    protected VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoPessoaUsuarioResource = $vinculacaoPessoaUsuarioResource;
    }

    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     *
     * @return void
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        // não tem request
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        // não tem direito de ver o processo
        if ((false === $this->authorizationChecker->isGranted('VIEW', $entity->getVolume()->getProcesso())) ||
            ($entity->getVolume()->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted(
                    'VIEW',
                    $entity->getVolume()->getProcesso()->getClassificacao()
                )))
        ) {
            $restDto = new JuntadaDTO();
            $restDto->setId($entity->getId());
            return;
        }

        // é usuário interno
        if ($this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            return;
        }

        // é usuário externo, mas o processo tem visibilidade externa
        if ($entity->getVolume()->getProcesso()->getVisibilidadeExterna()) {
            return;
        }

        // é usuário externo, mas o processo foi criado por ele no protocolo eletrônico
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            ($entity->getVolume()->getProcesso()->getCriadoPor()?->getId() ===
                $this->tokenStorage->getToken()->getUser()->getId())) {
            return;
        }

        // é usuário externo, mas é interessado no processo
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            foreach ($entity->getVolume()->getProcesso()->getInteressados() as $interessado) {
                if ($interessado->getPessoa()->getNumeroDocumentoPrincipal() ===
                    $this->tokenStorage->getToken()->getUserIdentifier()) {
                    return;
                }
            }
        }

        // está vinculado a um ofício recebido por um usuário externo
        if ($entity->getDocumento()->getDocumentoAvulsoRemessa()) {
            $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
                ->findOneBy([
                    'usuarioVinculado' => $this->tokenStorage->getToken()->getUser(),
                    'pessoa' => $entity->getDocumento()->getDocumentoAvulsoRemessa()->getPessoaDestino(),
                ]);

            if ($vinculacaoUsuario &&
                ($vinculacaoUsuario->getPessoa()->getId() ===
                    $entity->getDocumento()->getDocumentoAvulsoRemessa()->getPessoaDestino()->getId())) {
                return;
            }
        }

        // é usuário externo e tem chave de acesso
        if ((null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->chaveAcesso) &&
                (null !== $context->chaveAcesso) &&
                ($context->chaveAcesso
                    === $entity->getVolume()->getProcesso()->getChaveAcesso())) {
                return;
            }
        }

        //é usuário externo e criou a juntada
        if ($entity->getCriadoPor()?->getId() ===
            $this->tokenStorage->getToken()?->getUser()?->getId()) {
            return;
        }

        $assinado = false;
        /** @var ComponenteDigitalEntity $componenteDigitalEntity */
        foreach ($entity->getDocumento()->getComponentesDigitais() as $componenteDigitalEntity) {
            if ($componenteDigitalEntity->getAssinaturas()->count() > 0) {
                $assinado = true;
                break;
            }
        }

        if (!$assinado) {
            $restDto = new JuntadaDTO();
            $restDto->setId($entity->getId());
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
