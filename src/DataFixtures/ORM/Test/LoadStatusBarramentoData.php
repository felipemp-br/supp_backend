<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadStatusBarramentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;

/**
 * Class LoadStatusBarramentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadStatusBarramentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $statusBarramento = new StatusBarramento();
        $statusBarramento->setCodigoErro(null);
        $statusBarramento->setDocumentoAvulso(null);
        $statusBarramento->setCodSituacaoTramitacao(101);
        $statusBarramento->setIdt(101);
        $statusBarramento->setIdtComponenteDigital(null);
        $statusBarramento->setMensagemErro('MensagemErro-1');
        $statusBarramento->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $statusBarramento->setTramitacao(null);

        $manager->persist($statusBarramento);

        $this->addReference('StatusBarramento-'.$statusBarramento->getMensagemErro(), $statusBarramento);

        $statusBarramento = new StatusBarramento();
        $statusBarramento->setCodigoErro(null);
        $statusBarramento->setDocumentoAvulso(null);
        $statusBarramento->setCodSituacaoTramitacao(102);
        $statusBarramento->setIdt(102);
        $statusBarramento->setIdtComponenteDigital(null);
        $statusBarramento->setMensagemErro('MensagemErro-2');
        $statusBarramento->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $statusBarramento->setTramitacao(null);

        $manager->persist($statusBarramento);

        $this->addReference('StatusBarramento-'.$statusBarramento->getMensagemErro(), $statusBarramento);

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
