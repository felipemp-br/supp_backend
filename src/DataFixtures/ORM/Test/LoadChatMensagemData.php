<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadChatMensagemData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadChatMensagemData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadChatMensagemData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $chatMensagem = new ChatMensagem();
        $chatMensagem->setChat($this->getReference('Chat1', Chat::class));
        $chatMensagem->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $chatMensagem->setComponenteDigital(
            $this->getReference('ComponenteDigital-TEMPLATE OFÍCIO', ComponenteDigital::class)
        );
        $chatMensagem->setMensagem('MENSAGEM 0001');

        // Persist entity
        $manager->persist($chatMensagem);

        $chatMensagem = new ChatMensagem();
        $chatMensagem->setChat($this->getReference('Chat1', Chat::class));
        $chatMensagem->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $chatMensagem->setComponenteDigital(
            $this->getReference('ComponenteDigital-TEMPLATE OFÍCIO', ComponenteDigital::class)
        );
        $chatMensagem->setMensagem('MENSAGEM 0002');

        // Persist entity
        $manager->persist($chatMensagem);

        $chatMensagem = new ChatMensagem();
        $chatMensagem->setChat($this->getReference('Chat1', Chat::class));
        $chatMensagem->setUsuario($this->getReference('Usuario-00000000010', Usuario::class));
        $chatMensagem->setComponenteDigital(
            $this->getReference('ComponenteDigital-TEMPLATE OFÍCIO', ComponenteDigital::class)
        );
        $chatMensagem->setMensagem('MENSAGEM 0003');

        // Persist entity
        $manager->persist($chatMensagem);

        $chatMensagem = new ChatMensagem();
        $chatMensagem->setChat($this->getReference('Chat2', Chat::class));
        $chatMensagem->setUsuario($this->getReference('Usuario-00000000002', Usuario::class));
        $chatMensagem->setComponenteDigital(
            $this->getReference('ComponenteDigital-MODELO DESPACHO DE APROVAÇÃO', ComponenteDigital::class)
        );
        $chatMensagem->setMensagem('MENSAGEM 0004');

        // Persist entity
        $manager->persist($chatMensagem);

        $chatMensagem = new ChatMensagem();
        $chatMensagem->setChat($this->getReference('Chat2', Chat::class));
        $chatMensagem->setUsuario($this->getReference('Usuario-00000000006', Usuario::class));
        $chatMensagem->setComponenteDigital(
            $this->getReference('ComponenteDigital-MODELO DESPACHO DE APROVAÇÃO', ComponenteDigital::class)
        );
        $chatMensagem->setMensagem('MENSAGEM 0005');

        // Persist entity
        $manager->persist($chatMensagem);

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
