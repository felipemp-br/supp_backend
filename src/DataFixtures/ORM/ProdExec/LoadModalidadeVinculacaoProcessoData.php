<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeVinculacaoProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;

/**
 * Class LoadModalidadeVinculacaoProcessoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeVinculacaoProcessoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeVinculacaoProcesso = new ModalidadeVinculacaoProcesso();
        $modalidadeVinculacaoProcesso->setValor('APENSAMENTO');
        $modalidadeVinculacaoProcesso->setDescricao('APENSAMENTO');

        $manager->persist($modalidadeVinculacaoProcesso);

        $this->addReference('ModalidadeVinculacaoProcesso-'.$modalidadeVinculacaoProcesso->getValor(), $modalidadeVinculacaoProcesso);

        $modalidadeVinculacaoProcesso = new ModalidadeVinculacaoProcesso();
        $modalidadeVinculacaoProcesso->setValor('ANEXAÇÃO');
        $modalidadeVinculacaoProcesso->setDescricao('ANEXAÇÃO');

        $manager->persist($modalidadeVinculacaoProcesso);

        $this->addReference('ModalidadeVinculacaoProcesso-'.$modalidadeVinculacaoProcesso->getValor(), $modalidadeVinculacaoProcesso);

        $modalidadeVinculacaoProcesso = new ModalidadeVinculacaoProcesso();
        $modalidadeVinculacaoProcesso->setValor('REMISSÃO');
        $modalidadeVinculacaoProcesso->setDescricao('REMISSÃO');

        $manager->persist($modalidadeVinculacaoProcesso);

        $this->addReference('ModalidadeVinculacaoProcesso-'.$modalidadeVinculacaoProcesso->getValor(), $modalidadeVinculacaoProcesso);

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
