<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadServidorEmailData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ServidorEmail;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadServidorEmailData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadServidorEmailData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $servidorEmail = new ServidorEmail();
        $servidorEmail->setDescricao('Descrição 1');
        $servidorEmail->setNome('Nome 1');
        $servidorEmail->setHost('Host 1');
        $servidorEmail->setPorta(587);
        $servidorEmail->setProtocolo('IMAP');
        $servidorEmail->setMetodoEncriptacao('SMTP STARTTLS ');
        $servidorEmail->setAtivo(false);
        $servidorEmail->setValidaCertificado(false);
        $servidorEmail->setCriadoEm(new DateTime('now'));
        $servidorEmail->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($servidorEmail);

        $this->addReference('ServidorEmail-'.$servidorEmail->getNome(), $servidorEmail);

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
