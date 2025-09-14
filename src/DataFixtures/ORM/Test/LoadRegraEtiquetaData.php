<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeVinculacaoProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;

/**
 * Class LoadRegraEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRegraEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $regraEtiqueta = new RegraEtiqueta();
        $regraEtiqueta->setNome('REGRA-ETIQUETA-1');
        $regraEtiqueta->setDescricao('DESCRIÇÃO 1');
        $regraEtiqueta->setCriteria(null);
        $regraEtiqueta->setEtiqueta($this->getReference('Etiqueta-TESTE', Etiqueta::class));

        $manager->persist($regraEtiqueta);

        $this->addReference('RegraEtiqueta-'.$regraEtiqueta->getNome(), $regraEtiqueta);

        $regraEtiqueta = new RegraEtiqueta();
        $regraEtiqueta->setNome('REGRA-ETIQUETA-2');
        $regraEtiqueta->setDescricao('DESCRIÇÃO 2');
        $regraEtiqueta->setCriteria(null);
        $regraEtiqueta->setEtiqueta($this->getReference('Etiqueta-TESTE', Etiqueta::class));

        $manager->persist($regraEtiqueta);

        $this->addReference('RegraEtiqueta-'.$regraEtiqueta->getNome(), $regraEtiqueta);

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
