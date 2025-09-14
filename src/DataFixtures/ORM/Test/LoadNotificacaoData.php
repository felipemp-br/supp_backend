<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeVinculacaoProcessoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao;
use SuppCore\AdministrativoBackend\Entity\Notificacao;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadNotificacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadNotificacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $dataExpiracao1 = new DateTime('now +1 day');
        $notificacao = new Notificacao();
        $notificacao->setConteudo('CONTEÚDO 1');
        $notificacao->setContexto(null);
        $notificacao->setUrgente(false);
        $notificacao->setDestinatario($this->getReference('Usuario-00000000004', Usuario::class));
        $notificacao->setRemetente(null);
        $notificacao->setModalidadeNotificacao(
            $this->getReference('ModalidadeNotificacao-SISTEMA', ModalidadeNotificacao::class)
        );
        $notificacao->setDataHoraExpiracao($dataExpiracao1);
        $manager->persist($notificacao);

        $this->addReference('Notificacao-'.$notificacao->getConteudo(), $notificacao);

        $dataExpiracao2 = new DateTime('now +2 day');
        $notificacao = new Notificacao();
        $notificacao->setConteudo('CONTEÚDO 2');
        $notificacao->setContexto(null);
        $notificacao->setUrgente(false);
        $notificacao->setDestinatario($this->getReference('Usuario-00000000004', Usuario::class));
        $notificacao->setRemetente(null);
        $notificacao->setModalidadeNotificacao(
            $this->getReference('ModalidadeNotificacao-SISTEMA', ModalidadeNotificacao::class)
        );
        $notificacao->setDataHoraExpiracao($dataExpiracao2);
        $manager->persist($notificacao);

        $this->addReference('Notificacao-'.$notificacao->getConteudo(), $notificacao);

        $dataExpiracao3 = new DateTime('now +3 day');
        $notificacao = new Notificacao();
        $notificacao->setConteudo('CONTEÚDO 3');
        $notificacao->setContexto(null);
        $notificacao->setUrgente(false);
        $notificacao->setDestinatario($this->getReference('Usuario-00000000004', Usuario::class));
        $notificacao->setRemetente(null);
        $notificacao->setModalidadeNotificacao(
            $this->getReference('ModalidadeNotificacao-SISTEMA', ModalidadeNotificacao::class)
        );
        $notificacao->setDataHoraExpiracao($dataExpiracao3);
        $manager->persist($notificacao);

        $this->addReference('Notificacao-'.$notificacao->getConteudo(), $notificacao);

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
