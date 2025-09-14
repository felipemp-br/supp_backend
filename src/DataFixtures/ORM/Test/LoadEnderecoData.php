<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadEnderecoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Pais;
use SuppCore\AdministrativoBackend\Entity\Pessoa;

/**
 * Class LoadEnderecoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEnderecoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $endereco = new Endereco();
        $endereco->setMunicipio($this->getReference('Municipio-SÃO PAULO-SP', Municipio::class));
        $endereco->setPais($this->getReference('Pais-BR', Pais::class));
        $endereco->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));
        $endereco->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $endereco->setBairro('VILA ROMANA');
        $endereco->setLogradouro('RUA VESPASIANO');
        $endereco->setNumero('178');
        $endereco->setCep('05044050');
        $endereco->setObservacao('TESTE');
        $endereco->setPrincipal(true);

        // Persist entity
        $manager->persist($endereco);

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
        return 5;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
