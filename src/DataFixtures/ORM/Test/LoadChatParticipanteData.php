<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadChatParticipanteData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadChatParticipanteData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadChatParticipanteData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $chatParticipante = new ChatParticipante();
        $chatParticipante->setChat($this->getReference('Chat1', Chat::class));
        $chatParticipante->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $chatParticipante->setAdministrador(true);
        $chatParticipante->setMensagensNaoLidas(2);

        // Persist entity
        $manager->persist($chatParticipante);

        $chatParticipante = new ChatParticipante();
        $chatParticipante->setChat($this->getReference('Chat1', Chat::class));
        $chatParticipante->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $chatParticipante->setAdministrador(true);
        $chatParticipante->setMensagensNaoLidas(2);

        // Persist entity
        $manager->persist($chatParticipante);

        $chatParticipante = new ChatParticipante();
        $chatParticipante->setChat($this->getReference('Chat1', Chat::class));
        $chatParticipante->setUsuario($this->getReference('Usuario-00000000010', Usuario::class));
        $chatParticipante->setMensagensNaoLidas(2);

        // Persist entity
        $manager->persist($chatParticipante);

        $chatParticipante = new ChatParticipante();
        $chatParticipante->setChat($this->getReference('Chat2', Chat::class));
        $chatParticipante->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $chatParticipante->setMensagensNaoLidas(1);

        // Persist entity
        $manager->persist($chatParticipante);

        $chatParticipante = new ChatParticipante();
        $chatParticipante->setChat($this->getReference('Chat2', Chat::class));
        $chatParticipante->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $chatParticipante->setMensagensNaoLidas(1);

        // Persist entity
        $manager->persist($chatParticipante);

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
