<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Pipes/Tarefa/Pipe0004.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use function count;

/**
 * Class Pipe0004.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0004 implements PipeInterface
{
    /**
     * @param RequestStack    $requestStack
     * @param AssuntoResource $assuntoResource
     */
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly AssuntoResource $assuntoResource
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
            if (isset($context->assuntos)
                && $context->assuntos
                && null !== $restDto?->getProcesso()?->getAssuntos()
                && 0 === count($restDto?->getProcesso()?->getAssuntos())
            ) {
                $assuntos = $this->assuntoResource->getRepository()->findBy(
                    ['processo' => $entity->getProcesso()->getId()],
                    null,
                    5
                );

                if ($assuntos) {
                    $dtoClassName = $this->assuntoResource->getDtoClass();
                    $dtoMapper = $this->assuntoResource->getDtoMapperManager()->getMapper($dtoClassName);
                    $populate = [
                        'assuntoAdministrativo',
                    ];

                    foreach ($assuntos as $assunto) {
                        $dto = $dtoMapper->convertEntityToDto($assunto, $dtoClassName, $populate);
                        $restDto->getProcesso()->addAssunto($dto);
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
        return 4;
    }
}
