<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Pipes/Tarefa/Pipe0003.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\InteressadoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use function count;

/**
 * Class Pipe0003.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0003 implements PipeInterface
{
    /**
     * @param RequestStack        $requestStack
     * @param InteressadoResource $interessadoResource
     */
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly InteressadoResource $interessadoResource
    ) {
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        if (null !== $this->requestStack?->getCurrentRequest()?->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));

            // para evitar recursividade, verifica se a informação já existe no DTO
            if (isset($context->interessados)
                && $context->interessados
                && null !== $restDto?->getProcesso()?->getInteressados()
                && 0 === count($restDto?->getProcesso()?->getInteressados())
            ) {
                $interessados = $this->interessadoResource->getRepository()->findBy(
                    ['processo' => $entity->getProcesso()->getId()],
                    null,
                    5
                );

                if ($interessados) {
                    $dtoClassName = $this->interessadoResource->getDtoClass();
                    $dtoMapper = $this->interessadoResource->getDtoMapperManager()->getMapper($dtoClassName);
                    $populate = [
                        'pessoa',
                    ];

                    foreach ($interessados as $interessado) {
                        $dto = $dtoMapper->convertEntityToDto($interessado, $dtoClassName, $populate);
                        $restDto->getProcesso()->addInteressado($dto);
                    }
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }
}
