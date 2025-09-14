<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeEtiquetaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta;

/**
 * Class LoadModalidadeEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeEtiqueta = new ModalidadeEtiqueta();
        $modalidadeEtiqueta->setValor('TAREFA');
        $modalidadeEtiqueta->setDescricao('TAREFA');

        $manager->persist($modalidadeEtiqueta);

        $this->addReference('ModalidadeEtiqueta-'.$modalidadeEtiqueta->getValor(), $modalidadeEtiqueta);

        $modalidadeEtiqueta = new ModalidadeEtiqueta();
        $modalidadeEtiqueta->setValor('PROCESSO');
        $modalidadeEtiqueta->setDescricao('PROCESSO');

        $manager->persist($modalidadeEtiqueta);

        $this->addReference('ModalidadeEtiqueta-'.$modalidadeEtiqueta->getValor(), $modalidadeEtiqueta);

        $modalidadeEtiqueta = new ModalidadeEtiqueta();
        $modalidadeEtiqueta->setValor('DOCUMENTO');
        $modalidadeEtiqueta->setDescricao('DOCUMENTO');

        $manager->persist($modalidadeEtiqueta);

        $this->addReference('ModalidadeEtiqueta-'.$modalidadeEtiqueta->getValor(), $modalidadeEtiqueta);

        $modalidadeEtiqueta = new ModalidadeEtiqueta();
        $modalidadeEtiqueta->setValor('RELATORIO');
        $modalidadeEtiqueta->setDescricao('RELATORIO');

        $manager->persist($modalidadeEtiqueta);

        $this->addReference('ModalidadeEtiqueta-'.$modalidadeEtiqueta->getValor(), $modalidadeEtiqueta);

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
