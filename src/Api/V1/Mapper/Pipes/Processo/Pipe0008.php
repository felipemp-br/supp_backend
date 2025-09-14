<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Processo/Pipe0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Repository\BookmarkRepository;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;

/**
 * Class Pipe0008.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0008 implements PipeInterface
{
    public function __construct(private BookmarkRepository $bookmarkRepository)
    {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ProcessoDTO|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $restDto->setHasBookmark(
            !!$this->bookmarkRepository->countAdvanced(['processo.id' => 'eq:'.$entity->getId()])
        );
    }

    public function getOrder(): int
    {
        return 8;
    }
}
