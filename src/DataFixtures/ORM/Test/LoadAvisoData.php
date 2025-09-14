<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadAvisoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Aviso;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAvisoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAvisoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 8; ++$i) {
            $aviso = new Aviso();

            $aviso->setNome('NOME_'.$i);
            $aviso->setDescricao('Descrição Teste');
            $aviso->setSistema(true);
            $aviso->setAtivo(true);
            $aviso->setApagadoEm(null);
            $aviso->setAtualizadoEm(date_create('now'));
            $aviso->setCriadoEm(date_create('now'));
            $aviso->setCriadoPor($this->getReference('Usuario-00000000002', Usuario::class));

            $manager->persist($aviso);

            $this->addReference('Aviso-'.$aviso->getNome(), $aviso);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 7;
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
