<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadSetorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class LoadSetorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadSetorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $setor = new Setor();
        $setor->setNome('SECRETARIA-1');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getSigla(),
            $setor
        );

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
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
