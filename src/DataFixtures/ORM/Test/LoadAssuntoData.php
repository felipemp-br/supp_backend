<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAssuntoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAssuntoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAssuntoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $assunto = new Assunto();

        $assunto->setPrincipal(true);
        $assunto->setAssuntoAdministrativo(
            $this->getReference('AssuntoAdministrativo-RECURSOS HUMANOS', AssuntoAdministrativo::class)
        );
        $assunto->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $assunto->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $assunto->setApagadoEm(null);
        $assunto->setAtualizadoEm(date_create('now'));
        $assunto->setCriadoEm(date_create('now'));
        $assunto->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($assunto);

        $assunto = new Assunto();

        $assunto->setPrincipal(false);
        $assunto->setAssuntoAdministrativo(
            $this->getReference('AssuntoAdministrativo-DIREITO TRIBUTARIO', AssuntoAdministrativo::class)
        );
        $assunto->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $assunto->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $assunto->setApagadoEm(null);
        $assunto->setAtualizadoEm(date_create('now'));
        $assunto->setCriadoEm(date_create('now'));
        $assunto->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($assunto);

        $assunto = new Assunto();

        $assunto->setPrincipal(true);
        $assunto->setAssuntoAdministrativo(
            $this->getReference('AssuntoAdministrativo-DIREITO TRIBUTARIO', AssuntoAdministrativo::class)
        );
        $assunto->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $assunto->setProcesso($this->getReference('Processo-00100000002202022', Processo::class));
        $assunto->setApagadoEm(null);
        $assunto->setAtualizadoEm(date_create('now'));
        $assunto->setCriadoEm(date_create('now'));
        $assunto->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($assunto);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 6;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['test', 'testAssunto'];
    }
}
