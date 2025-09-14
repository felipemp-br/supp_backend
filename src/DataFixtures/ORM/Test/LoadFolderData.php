<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadFolderData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Folder;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFolder;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadFolderData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadFolderData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $folder = new Folder();
        $folder->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $folder->setModalidadeFolder($this->getReference('ModalidadeFolder-TAREFA', ModalidadeFolder::class));
        $folder->setNome('FOLDER TESTE');
        $folder->setDescricao('FOLDER TESTE');

        // Persist entity
        $manager->persist($folder);

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
        return 3;
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
