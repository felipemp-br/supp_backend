<?php

namespace SuppCore\AdministrativoBackend\Helpers\Configuration;

use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigurationManager
{
    /**
     * @var array|ConfigurationInterface[]
     */
    private array $configurations = [];

    /**
     * @param ConfigurationInterface $configuration
     * @return void
     */
    public function addConfiguration(ConfigurationInterface $configuration): void
    {
        $this->configurations[] = $configuration;
    }

    /**
     * @return array
     */
    public function getConfigurations(): array
    {
        $configuracoesExternas = [];
        foreach ($this->configurations as $configuration) {
            $configuracoesExternas[$configuration->getKey()] = $configuration->getValue();
        }
        return $configuracoesExternas;
    }
}
