<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Etiqueta/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Etiqueta;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\CoordenadorRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
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

    protected TokenStorageInterface $tokenStorage;

    protected VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository;

    /**
     * Pipe0001 constructor.
     *
     * @param TokenStorageInterface        $tokenStorage                  ;
     * @param VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository,
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository,
        protected LotacaoRepository $lotacaoRepository,
        protected CoordenadorRepository $coordenadorRepository
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoEtiquetaRepository = $vinculacaoEtiquetaRepository;
    }

    public function supports(): array
    {
        return [
            EtiquetaDTO::class => [
                'onCreateDTOFromEntity'
            ],
        ];
    }

    /**
     * @param EtiquetaDTO|RestDtoInterface|null $restDto
     * @param EtiquetaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (!$this->tokenStorage->getToken() ||
            !$this->tokenStorage->getToken()->getUser()) {
            return;
        }

        //CASO A ETIQUETA SEJA PRIVADA, NÃO PERMITE A VISUALIZAÇÃO DA ETIQUETA
        if ($entity->getPrivada()){
            $isLotado = true;
            $coordenadorSetor = false;
            $coordenadorUnidade = false;
            $coordenadorOrgaoCentral = false;
            $vincEtiqueta = $this->vinculacaoEtiquetaRepository->
                findVinculacaoEtiqueta($entity->getId());
            switch ($vincEtiqueta){
                case ($vincEtiqueta->getUsuario() !== null):
                    if ($this->tokenStorage->getToken()?->getUser()?->getId() !==
                        $entity->getCriadoPor()?->getId()){
                        $isLotado = false;
                    }
                    break;
                case ($vincEtiqueta->getSetor() !== null):
                    $isLotado = $this->lotacaoRepository->findIsLotadoSetor(
                        $vincEtiqueta->getSetor()->getId(),
                        $this->tokenStorage->getToken()?->getUser()?->getId()
                    );
                    $coordenadorSetor = $this->coordenadorRepository->findCoordenadorByUsuarioAndSetor(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getSetor()->getId()
                    );
                    $coordenadorUnidade = $this->coordenadorRepository->findCoordenadorByUsuarioAndUnidade(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getSetor()->getUnidade()->getId()
                    );
                    $coordenadorOrgaoCentral = $this->coordenadorRepository->findCoordenadorByUsuarioAndOrgaoCentral(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getSetor()->getUnidade()->getModalidadeOrgaoCentral()->getId()
                    );
                    break;
                case ($vincEtiqueta->getUnidade() !== null):
                    $isLotado = $this->lotacaoRepository->findIsLotadoUnidade(
                        $vincEtiqueta->getUnidade()->getId(),
                        $this->tokenStorage->getToken()?->getUser()?->getId()
                    );
                    $coordenadorUnidade = $this->coordenadorRepository->findCoordenadorByUsuarioAndUnidade(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getUnidade()->getId()
                    );
                    $coordenadorOrgaoCentral = $this->coordenadorRepository->findCoordenadorByUsuarioAndOrgaoCentral(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getUnidade()->getModalidadeOrgaoCentral()->getId()
                    );
                    break;
                case ($vincEtiqueta->getModalidadeOrgaoCentral() !== null):
                    $isLotado = $this->lotacaoRepository->findIsLotadoOrgaoCentral(
                        $vincEtiqueta->getModalidadeOrgaoCentral()->getId(),
                        $this->tokenStorage->getToken()?->getUser()?->getId()
                    );
                    $coordenadorOrgaoCentral = $this->coordenadorRepository->findCoordenadorByUsuarioAndOrgaoCentral(
                        $this->tokenStorage->getToken()?->getUser()?->getId(),
                        $vincEtiqueta->getModalidadeOrgaoCentral()->getId()
                    );
                    break;
            }

            if(!$isLotado &&
               !$coordenadorSetor &&
               !$coordenadorUnidade &&
               !$coordenadorOrgaoCentral)
            {
                    $restDto = new EtiquetaDTO();
                    $restDto->setId($entity->getId());
                    $restDto->setAtivo(false);
                    unset($restDto);
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
