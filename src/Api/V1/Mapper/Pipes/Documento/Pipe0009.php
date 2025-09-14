<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0009.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoDocumentoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Pipe0009.
 */
class Pipe0009 implements PipeInterface
{

    /**
     * Pipe0009 constructor.
     */
    public function __construct(
        private VinculacaoDocumentoRepository $vinculacaoDocumentoRepository,
        private RequestStack $requestStack
    ) {
    }

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
     * @param Documento|EntityInterface          $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if ((null !== $this->requestStack?->getCurrentRequest()?->get('context'))) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
            if (isset($context->verificaAnexos) && $context->verificaAnexos) {
                $restDto->setTemAnexos(
                    !!$this->vinculacaoDocumentoRepository->findByDocumento($restDto->getId())
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
