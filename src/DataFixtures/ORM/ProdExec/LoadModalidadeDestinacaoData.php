<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeDestinacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDestinacao;

/**
 * Class LoadModalidadeDestinacaoData.
 */
class LoadModalidadeDestinacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeDestinacao = new ModalidadeDestinacao();
        $modalidadeDestinacao->setValor('TRANSFERÊNCIA');
        $modalidadeDestinacao->setDescricao('TRANSFERÊNCIA');

        $manager->persist($modalidadeDestinacao);

        $this->addReference('ModalidadeDestinacao-'.$modalidadeDestinacao->getValor(), $modalidadeDestinacao);

        $modalidadeDestinacao = new ModalidadeDestinacao();
        $modalidadeDestinacao->setValor('RECOLHIMENTO');
        $modalidadeDestinacao->setDescricao('RECOLHIMENTO');

        $manager->persist($modalidadeDestinacao);

        $this->addReference('ModalidadeDestinacao-'.$modalidadeDestinacao->getValor(), $modalidadeDestinacao);

        $modalidadeDestinacao = new ModalidadeDestinacao();
        $modalidadeDestinacao->setValor('ELIMINAÇÃO');
        $modalidadeDestinacao->setDescricao('ELIMINAÇÃO');

        $manager->persist($modalidadeDestinacao);

        $this->addReference('ModalidadeDestinacao-'.$modalidadeDestinacao->getValor(), $modalidadeDestinacao);

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
