<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Pipes/Tarefa/Pipe0005.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelevanciaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use function count;

/**
 * Class Pipe0005.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0005 implements PipeInterface
{
    /**
     * @param RequestStack       $requestStack
     * @param RelevanciaResource $relevanciaResource
     */
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly RelevanciaResource $relevanciaResource
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
            if (isset($context->relevancias)
                && $context->relevancias
                && null !== $restDto?->getProcesso()?->getRelevancias()
                && 0 === count($restDto?->getProcesso()?->getRelevancias())
            ) {
                $relevancias = $this->relevanciaResource->getRepository()->findBy(
                    ['processo' => $entity->getProcesso()->getId()],
                    null,
                    5
                );

                if ($relevancias) {
                    $dtoClassName = $this->relevanciaResource->getDtoClass();
                    $dtoMapper = $this->relevanciaResource->getDtoMapperManager()->getMapper($dtoClassName);
                    $populate = [
                        'relevancia',
                        'especieRelevancia',
                    ];

                    foreach ($relevancias as $relevancia) {
                        $dto = $dtoMapper->convertEntityToDto($relevancia, $dtoClassName, $populate);
                        $restDto->getProcesso()->addRelevancia($dto);
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
        return 5;
    }
}
