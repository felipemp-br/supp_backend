<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadRelevanciaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelevancia;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Relevancia;

/**
 * Class LoadRelevanciaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadRelevanciaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $relevancia = new Relevancia();
        $relevancia->setObservacao('RELEVANCIA-1');
        $relevancia->setEspecieRelevancia(
            $this->getReference('EspecieRelevancia-PESSOA IDOSA', EspecieRelevancia::class)
        );
        $relevancia->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));

        $manager->persist($relevancia);

        $this->addReference('Relevancia-'.$relevancia->getObservacao(), $relevancia);

        $relevancia = new Relevancia();
        $relevancia->setObservacao('RELEVANCIA-2');
        $relevancia->setEspecieRelevancia(
            $this->getReference('EspecieRelevancia-ALTO VALOR ECONÔMICO', EspecieRelevancia::class)
        );
        $relevancia->setProcesso($this->getReference('Processo-00100000002202022', Processo::class));

        $manager->persist($relevancia);

        $this->addReference('Relevancia-'.$relevancia->getObservacao(), $relevancia);

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
