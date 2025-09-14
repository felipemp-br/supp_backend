<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadGeneroAtividadeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GeneroAtividade;

/**
 * Class LoadGeneroAtividadeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGeneroAtividadeData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $generoAtividade = new GeneroAtividade();
        $generoAtividade->setNome('ADMINISTRATIVO');
        $generoAtividade->setDescricao('ADMINISTRATIVO');

        $manager->persist($generoAtividade);

        $this->addReference(
            'GeneroAtividade-'.$generoAtividade->getNome(),
            $generoAtividade
        );

        $generoAtividade = new GeneroAtividade();
        $generoAtividade->setNome('ARQUIVÍSTICO');
        $generoAtividade->setDescricao('ARQUIVÍSTICO');

        $manager->persist($generoAtividade);

        $this->addReference(
            'GeneroAtividade-'.$generoAtividade->getNome(),
            $generoAtividade
        );

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
        return ['prodexec'];
    }
}
