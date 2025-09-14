<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\VinculacaoDocumentoAssinaturaExterna;

use Exception;
use function substr;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
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
        protected ComponenteDigitalRepository $componenteDigitalRepository,
        protected UsuarioRepository $usuarioRepository,
        protected AuthorizationCheckerInterface $authorizationChecker,
    ) { }
   public function supports(): array
    {
        return [
            VinculacaoDocumentoAssinaturaExternaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumentoAssinaturaExternaDTO $restDto 
     * @param VinculacaoDocumentoAssinaturaExterna $entity 
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if($restDto?->getId()) {
            if($entity->getNumeroDocumentoPrincipal()) {
                $restDto->setNumeroDocumentoPrincipal(substr($entity->getNumeroDocumentoPrincipal(), 0, 5).'******');
            }

            $usuario = $entity->getUsuario() ?? $this->usuarioRepository->findOneBy(['username' => $entity->getNumeroDocumentoPrincipal()]);
            if($usuario) {
                $restDto->setAssinado(
                    $entity->getId() && $this->componenteDigitalRepository->isAssinadoUser($entity->getDocumento()->getId(), $usuario->getId())
                );
            }

            $componentesDigitais = $entity->getDocumento()->getComponentesDigitais();
            foreach ($componentesDigitais as $componenteDigital) {
                $assinaturas = $componenteDigital->getAssinaturas();
                if(!$restDto->getPadraoAssinatura() && count($assinaturas)) {
                    $restDto->setPadraoAssinatura($assinaturas[0]->getPadrao());
                }
            }
        }

        // não tem direito de ver o documento
        if (false === $this->authorizationChecker->isGranted(
            'VIEW',
            $entity->getDocumento()
        )) {
            $restDto = new VinculacaoDocumentoAssinaturaExternaDTO();
            $restDto->setId($entity->getId());
            return;
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
