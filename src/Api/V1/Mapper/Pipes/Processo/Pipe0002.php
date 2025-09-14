<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
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
    protected AuthorizationCheckerInterface$authorizationChecker;

    protected RequestStack $requestStack;

    protected TokenStorageInterface $tokenStorage;

    protected VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource;

    /**
     * Pipe0002 constructor.
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
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        // Se o processo não tem visibilidade externa e está sendo acessado por um Usuário Externo,
        // valida convenio, protocolo eletrônico ou chave de acesso
        if ((false === $this->authorizationChecker->isGranted('ROLE_COLABORADOR'))
            && (false === $entity->getVisibilidadeExterna())
        ) {
            $temAcesso = false;
            $usuario = $this->tokenStorage->getToken()->getUser();

            if ((true === $this->authorizationChecker->isGranted('ROLE_PESSOA_VINCULADA'))) {
                /** @var Usuario $usuario */
                foreach ($usuario->getVinculacoesPessoasUsuarios() as $vinculacaoPessoaUsuario) {
                    foreach ($entity->getDocumentosAvulsos() as $documentoAvulso) {
                        if (($documentoAvulso->getDataHoraRemessa() && $documentoAvulso->getPessoaDestino()) &&
                            ($documentoAvulso->getPessoaDestino()->getId() ===
                                $vinculacaoPessoaUsuario->getPessoa()->getId())) {
                            $temAcesso = true;
                            break 2;
                        }
                    }
                }
            }

            if ($entity->getCriadoPor() &&
                $entity->getProtocoloEletronico() &&
                ($entity->getCriadoPor()->getId() === $usuario->getId())) {
                $temAcesso = true;
            }

            $context = null;
            if (null !== $this->requestStack->getCurrentRequest()->get('context')) {
                $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            }

            if (isset($context->chaveAcesso) && (null !== $context->chaveAcesso)) {
                //verifica se a chave de acesso está correta
                if ($context->chaveAcesso === $entity->getChaveAcesso()) {
                    $temAcesso = true;
                }
            }
            if (!$temAcesso) {
                $restDto = new ProcessoDTO();
                $restDto->setId($entity->getId());
                $restDto->setNUP($entity->getNUP());
                $restDto->setAcessoNegado(true);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
