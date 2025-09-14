<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Favorito/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Favorito;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Favorito as FavoritoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Favorito as FavoritoEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    protected EntityManagerInterface $em;

    private ContainerInterface $container;
    private SerializerInterface $serializer;
    protected RequestStack $requestStack;

    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        EntityManagerInterface $em,
        ContainerInterface $container,
        SerializerInterface $serializer,
        RequestStack $requestStack
    ) {
        $this->em = $em;
        $this->container = $container;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
    }

    public function supports(): array
    {
        return [
            FavoritoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param FavoritoDTO|RestDtoInterface|null $restDto
     * @param FavoritoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        //adicionando objeto a partir da classe e id
        if ($restDto && $this->requestStack->getCurrentRequest()) {
            $classe = $entity->getObjectClass();

            $resourceName = str_replace('\\Entity\\', '\\Api\\V1\\Resource\\', $classe).'Resource';

            /** @var RestResource $resource */
            $resource = $this->container->get($resourceName);

            $targetEntity = $resource->findOne($entity->getObjectId());

            $dtoMapper = $resource->getDtoMapperManager()->getMapper(
                $resource->getDtoClass()
            );

            $populate = RequestHandler::getPopulate($this->requestStack->getCurrentRequest(), $resource);

            $dto = $dtoMapper->convertEntityToDto(
                $targetEntity,
                $resource->getDtoClass(),
                $populate,
            );

            $serializedDto = json_decode($this->serializer->serialize(
                $dto,
                'json'
            ), true);

            $restDto->setObjFavoritoClass($serializedDto);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
