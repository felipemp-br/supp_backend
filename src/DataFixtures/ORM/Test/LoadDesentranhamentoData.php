<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadDesentranhamentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * Class LoadDesentranhamentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadDesentranhamentoData extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $desentranhamento = new Desentranhamento();
        $desentranhamento->setJuntada($this->getReference('Juntada-TESTE_11', Juntada::class));
        $desentranhamento->setProcessoDestino($this->getReference('Processo-00100000001202088', Processo::class));

        // Persist entity
        $manager->persist($desentranhamento);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 7;
    }
}
