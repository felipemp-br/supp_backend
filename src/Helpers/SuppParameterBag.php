<?php

declare(strict_types=1);

/**
 * /src/Helpers/SuppParameterBag.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfigModuloResource;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SuppParameterBag.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class SuppParameterBag
{
    public function __construct(
        private readonly ConfigModuloResource $configModuloResource,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    //    public function clear()
    //    {
    //        // TODO: Implement clear() method.
    //    }
    //
    //    public function add(array $parameters)
    //    {
    //        // TODO: Implement add() method.
    //    }
    //
    //    public function all()
    //    {
    //        // TODO: Implement all() method.
    //    }

    /**
     * @param string $paramId
     *
     * @return float|DateTime|array|bool|int|string|null
     */
    public function get(string $paramId): float|DateTime|array|bool|int|string|null
    {
        $config = $this->configModuloResource->getRepository()->findOneBy(['nome' => $paramId]);
        if ($config) {
            return $config->getValue();
        }

        $config = $this->configModuloResource->getRepository()->findOneBy(['sigla' => $paramId]);
        if ($config) {
            return $config->getValue();
        }

        return $this->parameterBag->get($paramId);
    }

    //    public function remove(string $name)
    //    {
    //        // TODO: Implement remove() method.
    //    }
    //
    //    public function set(string $name, $value)
    //    {
    //        // TODO: Implement set() method.
    //    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        $config = $this->configModuloResource->getRepository()->findOneBy(['nome' => $name]);
        if ($config && !$config->getInvalid()) {
            return true;
        }

        return $this->parameterBag->has($name);
    }

    /**
     * @param string $sigla
     *
     * @return bool
     */
    public function hasBySigla(string $sigla): bool
    {
        $config = $this->configModuloResource->getRepository()->findOneBy(['sigla' => $sigla]);

        return $config && !$config->getInvalid();
    }

    //    public function resolve()
    //    {
    //        // TODO: Implement resolve() method.
    //    }
    //
    //    public function resolveValue($value)
    //    {
    //        // TODO: Implement resolveValue() method.
    //    }
    //
    //    public function escapeValue($value)
    //    {
    //        // TODO: Implement escapeValue() method.
    //    }
    //
    //    public function unescapeValue($value)
    //    {
    //        // TODO: Implement unescapeValue() method.
    //    }
}
