<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadRamoDireitoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\RamoDireito;

/**
 * Class LoadRamoDireitoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRamoDireitoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['DIREITO À EDUCAÇÃO', 'EDU'],
            ['DIREITO ADMINISTRATIVO', 'ADM'],
            ['DIREITO AGRÁRIO', 'AGRA'],
            ['DIREITO AMBIENTAL', 'AMBI'],
            ['DIREITO ASSISTENCIAL', 'ASS'],
            ['DIREITO CIVIL', 'CIV'],
            ['DIREITO CONSTITUCIONAL', 'CONST'],
            ['DIREITO DA CRIANÇA E DO ADOLESCENTE', 'CAD'],
            ['DIREITO DA SAÚDE', 'SAU'],
            ['DIREITO DIGITAL', 'DIG'],
            ['DIREITO DO CONSUMIDOR', 'CONS'],
            ['DIREITO DO TRABALHO', 'TRAB'],
            ['DIREITO ECONÔMICO', 'ECON'],
            ['DIREITO ELEITORAL', 'ELEI'],
            ['DIREITO EMPRESARIAL', 'EMPR'],
            ['DIREITO FINANCEIRO', 'FIN'],
            ['DIREITO INTERNACIONAL', 'INT'],
            ['DIREITO MARÍTIMO', 'MAR'],
            ['DIREITO MINERÁRIO', 'MIN'],
            ['DIREITO PENAL', 'PEN'],
            ['DIREITO PREVIDENCIÁRIO', 'PREV'],
            ['DIREITO PROCESSUAL CIVIL E DO TRABALHO', 'PCT'],
            ['DIREITO PROCESSUAL PENAL', 'PPN'],
            ['DIREITO TRIBUTÁRIO', 'TRIB'],
            ['DIREITOS HUMANOS', 'HUM'],
            ['MILITARES', 'MIL'],
            ['SERVIDORES PÚBLICOS CIVIS', 'SERP'],
        ];

        foreach ($data as $valor) {
            $ramoDireito = (new RamoDireito())
                ->setNome($valor[0])
                ->setSigla($valor[1]);

            $manager->persist($ramoDireito);

            $this->addReference('RamoDireito-'.$ramoDireito->getNome(), $ramoDireito);
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
        return ['prod', 'dev', 'test', 'gest'];
    }
}
