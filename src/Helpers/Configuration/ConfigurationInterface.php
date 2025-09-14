<?php

namespace SuppCore\AdministrativoBackend\Helpers\Configuration;

interface ConfigurationInterface
{
    public function getKey(): string;
    public function getValue(): int|string|array;
}
