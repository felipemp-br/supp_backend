<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/VinculacaoEtiqueta/Pipe0002.php.
 *
 * @author Diego Ziquinatti - PGE-RS <diego@pge.rs.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoEtiqueta;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\CoordenadorRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeEtiquetaRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0002.
 *
 * @author Diego Ziquinatti - PGE-RS <diego@pge.rs.gov.br>
 */
class Pipe0002 implements PipeInterface
{
    private TokenStorageInterface $tokenStorage;

    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Pipe0002 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        protected VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository,
        protected LotacaoRepository $lotacaoRepository,
        protected CoordenadorRepository $coordenadorRepository,
        protected ModalidadeEtiquetaRepository $modalidadeEtiquetaRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDTO|RestDtoInterface|null $restDto
     * @param VinculacaoEtiquetaEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        VinculacaoEtiquetaDTO|RestDtoInterface|null &$restDto,
        VinculacaoEtiquetaEntity|EntityInterface $entity
    ): void
    {
        // CASO A ETIQUETA OU A VINCULAÇÃO DA ETIQUETA SEJA PRIVADA, NÃO PERMITE A VISUALIZAÇÃO DA ETIQUETA
        if ($entity->getEtiqueta()->getPrivada() || $entity->getPrivada()){
            $isLotado = true;
            $coordenadorSetor = false;
            $coordenadorUnidade = false;
            $coordenadorOrgaoCentral = false;
            if($entity->getEtiqueta()->getSistema()) {
                return;
            }
            $vincEtiqueta = $this->vinculacaoEtiquetaRepository->
                findVinculacaoEtiqueta($entity->getEtiqueta()->getId());
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
                        $vincEtiqueta->getUnidade()->getId(),
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
                !$coordenadorOrgaoCentral){
                $restDto = new VinculacaoEtiquetaDTO();
                $restDto->setId($entity->getId());
                unset($restDto);
            }
        }
    }

    public function getOrder(): int
    {
        return 0001;
    }
}
