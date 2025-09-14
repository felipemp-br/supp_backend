<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Documento/Pipe0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Documento;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0005.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0005 implements PipeInterface
{
    /**
     * Pipe0005 constructor.
     *
     * @param MapperManager $mapperManager
     * @param RequestStack  $requestStack
     */
    public function __construct(
        private MapperManager $mapperManager,
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
     * @param DocumentoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (isset($entity->getVinculacaoDocumentoPrincipal()[0])) {
            $restDto->setEstaVinculada(true);
            if ($this->requestStack->getCurrentRequest() &&
                null !== $this->requestStack->getCurrentRequest()->get('context')) {
                $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
                if (isset($context->incluiVinculacaoDocumentoPrincipal) &&
                    $context->incluiVinculacaoDocumentoPrincipal) {
                    $mapper = $this->mapperManager->getMapper(VinculacaoDocumento::class);
                    $populate = ['documento', 'documento.tipoDocumento', 'documento.componentesDigitais'];
                    if (!$entity->getVinculacaoDocumentoPrincipal()[0]->getDocumento()->getJuntadaAtual()) {
                        // apenas se for minuta
                        $populate[] = 'documento.tarefaOrigem';
                    }
                    /** @var VinculacaoDocumento $dto */
                    $dto = $mapper->convertEntityToDto(
                        $entity->getVinculacaoDocumentoPrincipal()[0],
                        VinculacaoDocumento::class,
                        $populate,
                    );

                    $restDto->setVinculacaoDocumentoPrincipal($dto);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
