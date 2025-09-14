<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadTramitacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;

/**
 * Class LoadTramitacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTramitacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tramitacao = new Tramitacao();
        $tramitacao->setProcesso($this->getReference('Processo-00100000005202066', Processo::class));
        $tramitacao->setMecanismoRemessa('MECANISMO DE REMESSA');
        $tramitacao->setPessoaDestino($this->getReference('Pessoa-12312312387', Pessoa::class));
        $tramitacao->setUsuarioRecebimento(null);
        $tramitacao->setDataHoraRecebimento(null);
        $tramitacao->setObservacao('OBSERVAÇÃO');
        $tramitacao->setSetorOrigem($this->getReference('Setor-ARQUIVO-AGU-SEDE', Setor::class));
        $tramitacao->setSetorDestino(null);

        // Persist entity
        $manager->persist($tramitacao);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 6;
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
