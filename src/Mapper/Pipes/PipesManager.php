<?php

declare(strict_types=1);
/**
 * /src/Mapper/Pipes/PipesManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper\Pipes;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\ImmutableService;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function get_class;
use function in_array;

/**
 * Class PipesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PipesManager
{
    public function __construct(
        private readonly ImmutableService $immutableService,
        private readonly ParameterBagInterface $parameterBag,
        #[Autowire('@service_container')]
        private readonly ContainerInterface $container,
    ) {
    }

    public function getPipes(string $className, string $context): array
    {
        $key = 'Pipes_For_'.str_replace('\\', '_', $className.'_'.$context);
        if ($this->parameterBag->has($key)) {
            return $this->parameterBag->get($key);
        }
        return [];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function proccess(
        ?RestDtoInterface &$restDto,
        EntityInterface $entity,
        string $context
    ): void {
        if ($this->immutableService->isImmutable($entity)) {
            $restDto->setImmutable(true);
        }

        $className = get_class($restDto);

        foreach ($this->getPipes($className, $context) as $pipeClass) {
            /** @var PipeInterface $pipe */
            $pipe = $this->container->get($pipeClass);
            $supports = $pipe->supports();
            if (array_key_exists($className, $supports)
                && in_array($context, $supports[$className], true)) {
                $pipe->execute($restDto, $entity);
            }
        }
    }
}
