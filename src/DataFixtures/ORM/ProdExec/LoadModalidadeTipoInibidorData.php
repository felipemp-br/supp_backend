<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeTipoInibidorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTipoInibidor;

/**
 * Class LoadModalidadeTipoInibidorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeTipoInibidorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeTipoInibidor = new ModalidadeTipoInibidor();
        $modalidadeTipoInibidor->setValor('SENHA');
        $modalidadeTipoInibidor->setDescricao('SENHA');

        $manager->persist($modalidadeTipoInibidor);

        $this->addReference('ModalidadeTipoInibidor-'.$modalidadeTipoInibidor->getValor(), $modalidadeTipoInibidor);

        $modalidadeTipoInibidor = new ModalidadeTipoInibidor();
        $modalidadeTipoInibidor->setValor('CERTIFICADO DIGITAL');
        $modalidadeTipoInibidor->setDescricao('CERTIFICADO DIGITAL');

        $manager->persist($modalidadeTipoInibidor);

        $this->addReference('ModalidadeTipoInibidor-'.$modalidadeTipoInibidor->getValor(), $modalidadeTipoInibidor);

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
