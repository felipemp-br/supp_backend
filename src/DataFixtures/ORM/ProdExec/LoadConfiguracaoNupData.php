<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadConfiguracaoNupData.php.
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ConfiguracaoNup;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;

/**
 * Class LoadConfiguracaoNupData.
 */
class LoadConfiguracaoNupData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(
        private readonly ?NUPProviderManager $nupProviderManager = null
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->nupProviderManager->getAllNupProviders() as $provider) {
            $configuracaoNup = new ConfiguracaoNup();
            $configuracaoNup->setNome($provider->getNome());
            $configuracaoNup->setDescricao($provider->getDescricao());
            $configuracaoNup->setSigla($provider->getSigla());
            $configuracaoNup->setDataHoraInicioVigencia($provider->getDataHoraInicioVigencia());
            $configuracaoNup->setDataHoraFimVigencia($provider->getDataHoraFimVigencia());
            $manager->persist($configuracaoNup);
            $this->addReference('ConfiguracaoNup-'.$configuracaoNup->getNome(), $configuracaoNup);
        }

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['prodexec'];
    }
}
