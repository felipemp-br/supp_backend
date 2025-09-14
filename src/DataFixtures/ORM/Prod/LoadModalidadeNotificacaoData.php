<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeNotificacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao;

/**
 * Class LoadModalidadeNotificacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeNotificacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeNotificacao = new ModalidadeNotificacao();
        $modalidadeNotificacao->setValor('SISTEMA');
        $modalidadeNotificacao->setDescricao('NOTIFICAÇÃO GERADA PELO SISTEMA');

        $manager->persist($modalidadeNotificacao);

        $this->addReference('ModalidadeNotificacao-'.$modalidadeNotificacao->getValor(), $modalidadeNotificacao);

        $modalidadeNotificacao = new ModalidadeNotificacao();
        $modalidadeNotificacao->setValor('MENSAGEM');
        $modalidadeNotificacao->setDescricao('NOTIFICAÇÃO ENVIADA DE UM USUÁRIO PARA OUTRO');

        $manager->persist($modalidadeNotificacao);

        $this->addReference('ModalidadeNotificacao-'.$modalidadeNotificacao->getValor(), $modalidadeNotificacao);

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
        return ['prod', 'dev', 'test'];
    }
}
