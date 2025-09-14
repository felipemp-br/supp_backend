<?php

// PROD
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEstadoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Estado;
use SuppCore\AdministrativoBackend\Entity\Pais;

/**
 * Class LoadEstadoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEstadoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $estado = new Estado();
        $estado->setNome('ACRE');
        $estado->setUf('AC');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('ALAGOAS');
        $estado->setUf('AL');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('AMAZONAS');
        $estado->setUf('AM');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('AMAPÁ');
        $estado->setUf('AP');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('BAHIA');
        $estado->setUf('BA');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('CEARÁ');
        $estado->setUf('CE');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('DISTRITO FEDERAL');
        $estado->setUf('DF');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('ESPÍRITO SANTO');
        $estado->setUf('ES');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('GOIÁS');
        $estado->setUf('GO');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('MARANHÃO');
        $estado->setUf('MA');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('MINAS GERAIS');
        $estado->setUf('MG');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('MATO GROSSO DO SUL');
        $estado->setUf('MS');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('MATO GROSSO');
        $estado->setUf('MT');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('PARÁ');
        $estado->setUf('PA');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('PARAÍBA');
        $estado->setUf('PB');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('PERNAMBUCO');
        $estado->setUf('PE');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('PIAUÍ');
        $estado->setUf('PI');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('PARANÁ');
        $estado->setUf('PR');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('RIO DE JANEIRO');
        $estado->setUf('RJ');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('RIO GRANDE DO NORTE');
        $estado->setUf('RN');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('RONDÔNIA');
        $estado->setUf('RO');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('RORAIMA');
        $estado->setUf('RR');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('RIO GRANDE DO SUL');
        $estado->setUf('RS');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('SANTA CATARINA');
        $estado->setUf('SC');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('SERGIPE');
        $estado->setUf('SE');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('SÃO PAULO');
        $estado->setUf('SP');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

        $estado = new Estado();
        $estado->setNome('TOCANTINS');
        $estado->setUf('TO');
        $estado->setPais($this->getReference('Pais-BR', Pais::class));
        $this->addReference('Estado-'.$estado->getUf(), $estado);
        $manager->persist($estado);

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
        return 2;
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
