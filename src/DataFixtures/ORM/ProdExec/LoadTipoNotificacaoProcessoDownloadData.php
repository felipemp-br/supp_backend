<?php

/** @noinspection ProblematicWhitespace */

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadTipoNotificacaoProcessoDownloadData.php.
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao;

/**
 * Class LoadTipoNotificacaoProcessoDownloadData.
 */
class LoadTipoNotificacaoProcessoDownloadData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tipoNotificacao = new TipoNotificacao();
        $tipoNotificacao->setNome('DOWNLOAD PROCESSO');
        $tipoNotificacao->setDescricao('DOWNLOAD PROCESSO');
        $manager->persist($tipoNotificacao);

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
        return ['prodexec'];
    }
}
