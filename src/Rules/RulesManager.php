<?php

declare(strict_types=1);
/**
 * /src/Rules/RulesManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rules;

use Doctrine\Persistence\Proxy;
use ProxyManager\Proxy\GhostObjectInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\VarExporter\LazyObjectInterface;
use function get_class;

/**
 * Class RulesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RulesManager
{
    private array $disabledRules = [];

    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ParameterBagInterface $parameterBag,
        #[Autowire('@service_container')]
        private readonly ContainerInterface $container,
    ) {
    }

    public function getRules(string $className, string $context): array
    {
        $key = 'Rules_For_'.str_replace('\\', '_', $className.'_'.$context);
        if ($this->parameterBag->has($key)) {
            return $this->parameterBag->get($key);
        }
        return [];
    }

    public function getRulesForEntity(string $className): array
    {
        $key = 'Rules_For_Entity_'.str_replace('\\', '_', $className);
        if ($this->parameterBag->has($key)) {
            return $this->parameterBag->get($key);
        }
        return [];
    }

    public function disableRule(
        string $rule,
        string|array $context = [
            'assertCreate', 'beforeCreate', 'assertUpdate',
            'beforeUpdate', 'assertPatch','beforePatch'
        ]
    ): void {
        $this->disabledRules[$rule] = array_merge(
            $this->disabledRules[$rule] ?? [],
            is_array($context) ? $context : [$context]
        );
    }

    public function enableRule(
        string $rule,
        string|array $context = [
            'assertCreate', 'beforeCreate', 'assertUpdate',
            'beforeUpdate', 'assertPatch','beforePatch'
        ]
    ): void {
        if ($this->disabledRules[$rule] ?? false) {
            $this->disabledRules[$rule] = array_diff(
                $this->disabledRules[$rule],
                is_array($context) ? $context : [$context]
            );
            if (!$this->disabledRules[$rule]) {
                unset($this->disabledRules[$rule]);
            }
        }
    }

    public function isDisabledRule(object $rule, string $contexto): bool
    {
        $ruleName = $rule instanceof LazyObjectInterface ?
            get_parent_class($rule) : get_class($rule);
        return in_array($contexto, $this->disabledRules[$ruleName] ?? []);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function proccess(
        ?RestDtoInterface $restDto,
        EntityInterface $entity,
        ?string $transactionId,
        string $contexto,
        ?array $context = null
    ): void {
        if (null !== $restDto) {
            $className = get_class($restDto);
        } else {
            // delete
            $className = ($entity instanceof Proxy || $entity instanceof GhostObjectInterface) ?
                get_parent_class($entity) : get_class($entity);
        }

        foreach ($this->getRules($className, $contexto) as $ruleClass) {
            /** @var RuleInterface $rule */
            $rule = $this->container->get($ruleClass);

            $supports = $rule->supports();
            if (array_key_exists($className, $supports) &&
                \in_array($contexto, $supports[$className], true)) {
                if ($this->isDisabledRule($rule, $contexto)) {
                    continue;
                }

                if ((('cli' === php_sapi_name()) && \in_array('skipWhenCommand', $supports[$className])) ||
                    (('cli' !== php_sapi_name()) && $this->tokenStorage->getToken()
                        && $this->authorizationChecker->isGranted('ROLE_ROOT'))
                ) {
                    continue;
                }

                $rule->validate($restDto, $entity, $transactionId);
            }
        }
    }
}
