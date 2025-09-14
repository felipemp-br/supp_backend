<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeTransicaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTransicao;

/**
 * Class LoadModalidadeTransicaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeTransicaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeTransicao = new ModalidadeTransicao();
        $modalidadeTransicao->setValor('TRANSFERÊNCIA');
        $modalidadeTransicao->setDescricao('TRANSFERÊNCIA');

        $manager->persist($modalidadeTransicao);

        $this->addReference('ModalidadeTransicao-'.$modalidadeTransicao->getValor(), $modalidadeTransicao);

        $modalidadeTransicao = new ModalidadeTransicao();
        $modalidadeTransicao->setValor('RECOLHIMENTO');
        $modalidadeTransicao->setDescricao('RECOLHIMENTO');

        $manager->persist($modalidadeTransicao);

        $this->addReference('ModalidadeTransicao-'.$modalidadeTransicao->getValor(), $modalidadeTransicao);

        $modalidadeTransicao = new ModalidadeTransicao();
        $modalidadeTransicao->setValor('DESARQUIVAMENTO');
        $modalidadeTransicao->setDescricao('DESARQUIVAMENTO');

        $manager->persist($modalidadeTransicao);

        $this->addReference('ModalidadeTransicao-'.$modalidadeTransicao->getValor(), $modalidadeTransicao);

        $modalidadeTransicao = new ModalidadeTransicao();
        $modalidadeTransicao->setValor('ELIMINAÇÃO');
        $modalidadeTransicao->setDescricao('ELIMINAÇÃO');

        $manager->persist($modalidadeTransicao);

        $this->addReference('ModalidadeTransicao-'.$modalidadeTransicao->getValor(), $modalidadeTransicao);

        $modalidadeTransicao = new ModalidadeTransicao();
        $modalidadeTransicao->setValor('EXTRAVIO');
        $modalidadeTransicao->setDescricao('EXTRAVIO');

        $manager->persist($modalidadeTransicao);

        $this->addReference('ModalidadeTransicao-'.$modalidadeTransicao->getValor(), $modalidadeTransicao);

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
