<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAvaliacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Avaliacao;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado;

/**
 * Class LoadAvaliacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAvaliacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $avaliacao = new Avaliacao();
        $avaliacao->setObjetoAvaliado($this->getReference('ObjetoAvaliado-1', ObjetoAvaliado::class));
        $avaliacao->setAvaliacao(100);

        // Persist entity
        $manager->persist($avaliacao);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
