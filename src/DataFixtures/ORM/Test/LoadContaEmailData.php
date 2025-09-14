<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadContaEmailData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ContaEmail;
use SuppCore\AdministrativoBackend\Entity\ServidorEmail;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class LoadContaEmailData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadContaEmailData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $contaEmail = new ContaEmail();
        $contaEmail->setServidorEmail($this->getReference('ServidorEmail-Nome 1', ServidorEmail::class));
        $contaEmail->setSetor($this->getReference('Setor-SECRETARIA-1-SECR', Setor::class));
        $contaEmail->setAtivo(true);
        $contaEmail->setLogin('LOGIN');
        $contaEmail->setSenha('PASS');
        $contaEmail->setNome('NOME');
        $contaEmail->setDescricao('DESCRIÇÃO');

        // Persist entity
        $manager->persist($contaEmail);

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
        return ['test'];
    }
}
