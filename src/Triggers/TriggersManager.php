<?php

declare(strict_types=1);
/**
 * /src/Triggers/TriggersManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Triggers;

use Doctrine\Persistence\Proxy;
use ProxyManager\Proxy\GhostObjectInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function in_array;

/**
 * Class TriggersManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TriggersManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ParameterBagInterface $parameterBag,
        #[Autowire('@service_container')]
        private readonly ContainerInterface $container,
    ) {
    }

    /**
     * Retorna o tipo de trigger.
     *
     * @param TriggerInterface $trigger
     *
     * @return TriggerTypeEnum
     */
    public static function getTriggerType(
        TriggerInterface $trigger
    ): TriggerTypeEnum {
        return match (true) {
            $trigger instanceof TriggerReadOneInterface => TriggerTypeEnum::READ_ONE,
            $trigger instanceof TriggerReadInterface => TriggerTypeEnum::READ,
            default => TriggerTypeEnum::WRITE,
        };
    }

    /**
     * Retorna a chave que contem as triggers suportadas pela classe com base no contexto.
     *
     * @param TriggerTypeEnum $triggerType
     * @param string          $className
     * @param string          $context
     *
     * @return string
     */
    public static function getTriggersSupportedKey(
        TriggerTypeEnum $triggerType,
        string $className,
        string $context
    ): string {
        return $triggerType->value.str_replace('\\', '_', $className.'_'.$context);
    }

    /**
     * Retorna as classnames supportadas para verificação da execução da trigger.
     *
     * @param string|object $objectRef
     *
     * @return array
     *
     * @throws ReflectionException
     */
    public static function getSupportedClassNames(
        string|object $objectRef
    ): array {
        $reflectionClass = new ReflectionClass($objectRef);
        return [
            ($objectRef instanceof Proxy || $objectRef instanceof GhostObjectInterface) ?
                $reflectionClass->getParentClass()->getName() : $reflectionClass->getName(),
            ...array_filter(
                $reflectionClass->getInterfaceNames(),
                fn ($interfaceName) => !in_array(
                    $interfaceName,
                    [
                        EntityInterface::class,
                        RestDtoInterface::class,
                    ]
                )
            ),
        ];
    }

    /**
     * Retorna as triggers com base no objeto de referencia, tipo de trigger e o contexto.
     *
     * @param TriggerTypeEnum $triggerType
     * @param string|object   $objectRef
     * @param string          $context
     *
     * @return TriggerInterface[]
     *
     * @throws ReflectionException
     */
    public function getTriggers(
        TriggerTypeEnum $triggerType,
        string|object $objectRef,
        string $context
    ): array {
        $isCli = 'cli' === php_sapi_name();
        $triggers = [];
        foreach (self::getSupportedClassNames($objectRef) as $className) {
            $key = self::getTriggersSupportedKey($triggerType, $className, $context);
            if ($this->parameterBag->has($key)) {
                $triggerClassnames = $this->parameterBag->get($key);
                foreach ($triggerClassnames as $triggerClassname) {
                    /** @var TriggerInterface $trigger */
                    $trigger = $this->container->get($triggerClassname);
                    $supports = $trigger->supports();
                    $isUnsupported = !isset($supports[$className]) //Não configurada;
                        || !in_array($context, $supports[$className]) //Não suportada;
                        || ($isCli && in_array('skipWhenCommand', $supports[$className])); //Deve ser ignorada em cli;
                    if ($isUnsupported) {
                        continue;
                    }
                    $triggers[] = $trigger;
                }
            }
        }
        $triggers = array_unique($triggers, SORT_REGULAR);
        // Ordena as triggers
        uasort($triggers, fn(TriggerInterface $a, TriggerInterface $b) => $a->getOrder() <=> $b->getOrder());

        return $triggers;
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     * @param string                $support
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function proccess(
        ?RestDtoInterface $restDto,
        EntityInterface $entity,
        string $transactionId,
        string $support
    ): void {
        if ($this->shouldExecuteTrigger()) {
            $triggers = $this->getTriggers(TriggerTypeEnum::WRITE,$restDto ?? $entity, $support);
            foreach ($triggers as $trigger) {
                $trigger->execute($restDto, $entity, $transactionId);
            }
        }
    }

    /**
     * Verifica se a trigger deve ser executada.
     *
     * @return bool
     */
    private function shouldExecuteTrigger(): bool {
        if ('cli' !== php_sapi_name()
            && $this->tokenStorage->getToken()
            && $this->authorizationChecker->isGranted('ROLE_ROOT')) {
            return false;
        }
        return true;
    }

    /**
     * @param string $className
     * @param array  $criteria
     * @param array  $orderBy
     * @param int    $limit
     * @param int    $offset
     * @param array  $populate
     * @param array  $result
     * @param string $support
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function proccessRead(
        string $className,
        array &$criteria,
        array &$orderBy,
        int &$limit,
        int &$offset,
        array &$populate,
        array &$result,
        string $support
    ): void {
        if ($this->shouldExecuteTrigger()) {
            $triggers = $this->getTriggers(
                TriggerTypeEnum::READ,
                $className,
                $support
            );
            foreach ($triggers as $trigger) {
                $trigger->execute($criteria, $orderBy, $limit, $offset, $populate, $result);
            }
        }
    }

    /**
     * @param string               $className
     * @param int                  $id
     * @param array|null           $populate
     * @param array|null           $orderBy
     * @param array|null           $context
     * @param EntityInterface|null $entity
     * @param string               $support
     *
     * @return void
     *
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function proccessReadOne(
        string $className,
        int &$id,
        ?array &$populate,
        ?array &$orderBy,
        ?array &$context,
        ?EntityInterface &$entity,
        string $support
    ): void {
        if ($this->shouldExecuteTrigger()) {
            $triggers = $this->getTriggers(
                TriggerTypeEnum::READ_ONE,
                $className,
                $support
            );
            foreach ($triggers as $trigger) {
                $trigger->execute($id, $populate, $orderBy, $context, $entity);
            }
        }
    }
}
