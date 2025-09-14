<?php

namespace SuppCore\AdministrativoBackend\Helpers\Configuration\Drivers;

use SuppCore\AdministrativoBackend\Helpers\Configuration\ConfigurationInterface;

class ConfigurationExample implements ConfigurationInterface
{

    public function getKey(): string
    {
        return "version";
    }

    public function getValue(): int|string|array
    {
        return "1.15.0";
    }
}
