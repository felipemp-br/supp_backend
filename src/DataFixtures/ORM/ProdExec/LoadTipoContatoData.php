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
use SuppCore\AdministrativoBackend\Entity\TipoContato;

/**
 * Class LoadTipoContatoData.
 */
class LoadTipoContatoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'nome' => 'UNIDADE',
                'descricao' => 'UNIDADE',
                'ativo' => true,
            ],
            [
                'nome' => 'SETOR',
                'descricao' => 'SETOR',
                'ativo' => true,
            ],
            [
                'nome' => 'USUÁRIO',
                'descricao' => 'USUÁRIO',
                'ativo' => true,
            ],
        ];

        foreach ($data as $item) {
            $tipoContato = (new TipoContato())
                ->setNome($item['nome'])
                ->setDescricao($item['descricao'])
                ->setAtivo($item['ativo']);

            $this->addReference('TipoContato-'.$tipoContato->getNome(), $tipoContato);
            $manager->persist($tipoContato);
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
