<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Usuario/Pipe0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Usuario;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0003.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    protected RequestStack $requestStack;

    protected TokenStorageInterface $tokenStorage;

    protected AfastamentoRepository $afastamentoRepository;

    protected SetorResource $setorResource;

    protected LotacaoResource $lotacaoResource;

    /**
     * Pipe0003 constructor.
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        AfastamentoRepository $afastamentoRepository,
        SetorResource $setorResource,
        LotacaoResource $lotacaoResource
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->afastamentoRepository = $afastamentoRepository;
        $this->setorResource = $setorResource;
        $this->lotacaoResource = $lotacaoResource;
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param UsuarioDTO|RestDtoInterface|null $restDto
     * @param UsuarioEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        $context = null;
        if (null !== $this->requestStack->getCurrentRequest()->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
        }

        // regra geral, usuario disponivel para receber tarefas
        $restDto->setIsDisponivel(true);
        $temDistribuidorDisponivel = false;

        // REGRA 1 - busca os distribuidores do setor, verifica se tem disponível
        // caso não tenha nenhum distribuidor disponível cancela a regra e outros usuários podem receber tarefas
        if (isset($context->setorApenasDistribuidor) && $context->setorApenasDistribuidor) {
            // trazemos os distribuidores que estao lotados no setor como distribuidor
            $lotacaoDistribuidores = $this->lotacaoResource->getRepository()
                ->findLotacaoDistribuidor($context->setorApenasDistribuidor);

            if ($lotacaoDistribuidores) {
                // verifica se dentre os distribuidores do setor existem disponíveis
                /** @var Lotacao $lotacaoDist */
                foreach ($lotacaoDistribuidores as $lotacaoDist) {
                    if (!$this->afastamentoRepository->findAfastamento(
                        $lotacaoDist->getColaborador()->getId()
                    )) {
                        $temDistribuidorDisponivel = true;
                        // verifica se o usuario do loop é o distribuidor e já seta
                        if ($lotacaoDist->getColaborador()->getId() ===
                            $entity->getColaborador()->getId()) {
                            $restDto->setIsDisponivel(true);

                            return;
                        } else {
                            $restDto->setIsDisponivel(false);
                        }

                        // se o usuario for distribuidor e está afastado fica indisponível
                    } elseif ($lotacaoDist->getColaborador()->getId() ===
                        $entity->getColaborador()->getId()) {
                        $restDto->setIsDisponivel(false);

                    // se o usuario não é distribuidor mas está afastado fica indisponível
                    } else {
                        if ($this->afastamentoRepository->findAfastamento(
                            $entity->getColaborador()->getId()
                        )) {
                            $restDto->setIsDisponivel(false);
                        }
                    }
                }
            }
        }

        // REGRA 2 - caso setor nao seja de distribuidor,
        // verifica se o usuario esta afastado e não pode receber tarefas
        if (!$temDistribuidorDisponivel &&
            isset($context->semAfastamento) && $context->semAfastamento) {
            if ($this->afastamentoRepository->findAfastamento(
                $entity->getColaborador()->getId()
            )) {
                $restDto->setIsDisponivel(false);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
