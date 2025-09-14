<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/VinculacaoProcesso/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoProcesso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeVinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
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
        protected NUPProviderManager $nupProviderManager,
        protected ModalidadeVinculacaoProcessoResource $modalidadeVinculacaoProcessoResource
    ) {}

    public function supports(): array
    {
        return [
            VinculacaoProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoProcessoDTO|RestDtoInterface|null $restDto
     * @param VinculacaoProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        VinculacaoProcessoDTO|RestDtoInterface|null &$restDto,
        VinculacaoProcessoEntity|EntityInterface $entity
    ): void {
        // não tem request
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        // não tem direito de ver o processo
        if ((false === $this->authorizationChecker->isGranted('VIEW', $entity->getProcesso())) ||
            ($entity->getProcesso()->getClassificacao() &&
                (false === $this->authorizationChecker->isGranted('VIEW', $entity->getProcesso()->getClassificacao())))
        ) {
            $modalidade = $restDto->getModalidadeVinculacaoProcesso();
            $restDto = new VinculacaoProcessoDTO();
            $restDto->setId($entity->getId());
            $processo = new ProcessoDTO();
            $processo->setId($entity->getProcesso()->getId());
            $processo->setNUP($entity->getProcesso()->getNUP());
            $processo->setNUPFormatado($this->nupProviderManager->getNupProvider($entity->getProcesso())
                ->formatarNumeroUnicoProtocolo($entity->getProcesso()->getNUP()));
            $processo->setAcessoNegado(true);
            $restDto->setProcesso($processo);
            $processoVinculado = new ProcessoDTO();
            $processoVinculado->setId($entity->getProcessoVinculado()->getId());
            $processoVinculado->setNUP($entity->getProcessoVinculado()->getNUP());
            $processoVinculado->setNUPFormatado($this->nupProviderManager->getNupProvider($entity->getProcessoVinculado())
                ->formatarNumeroUnicoProtocolo($entity->getProcessoVinculado()->getNUP()));
            $restDto->setProcessoVinculado($processoVinculado);
            $restDto->setModalidadeVinculacaoProcesso($modalidade);
            return;
        }

        // é usuário interno
        if ($this->authorizationChecker->isGranted('ROLE_COLABORADOR')) {
            return;
        }

        // é usuário externo, mas o processo tem visibilidade externa
        if ($entity->getProcesso()->getVisibilidadeExterna()) {
            return;
        }

        // é usuário externo e tem chave de acesso
        if ((null !== $this->requestStack->getCurrentRequest()->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->chaveAcesso) &&
                ($context->chaveAcesso
                    === $entity->getProcesso()->getChaveAcesso())) {
                return;
            }
        }

        $modalidade = $restDto->getModalidadeVinculacaoProcesso();
        $restDto = new VinculacaoProcessoDTO();
        $restDto->setId($entity->getId());
        $processo = new ProcessoDTO();
        $processo->setId($entity->getProcesso()->getId());
        $processo->setNUP($entity->getProcesso()->getNUP());
        $processo->setNUPFormatado($this->nupProviderManager->getNupProvider($entity->getProcesso())
            ->formatarNumeroUnicoProtocolo($entity->getProcesso()->getNUP()));
        $processo->setAcessoNegado(true);
        $restDto->setProcesso($processo);
        $processoVinculado = new ProcessoDTO();
        $processoVinculado->setId($entity->getProcessoVinculado()->getId());
        $processoVinculado->setNUP($entity->getProcessoVinculado()->getNUP());
        $processoVinculado->setNUPFormatado($this->nupProviderManager->getNupProvider($entity->getProcessoVinculado())
            ->formatarNumeroUnicoProtocolo($entity->getProcessoVinculado()->getNUP()));
        $restDto->setProcessoVinculado($processoVinculado);
        $restDto->setModalidadeVinculacaoProcesso($modalidade);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
