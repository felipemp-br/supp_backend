<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadGeneroDocumentoAvulsoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumentoAvulso;

/**
 * Class LoadGeneroDocumentoAvulsoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadGeneroDocumentoAvulsoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $generoDocumentoAvulso = new GeneroDocumentoAvulso();
        $generoDocumentoAvulso->setNome('ADMINISTRATIVO');
        $generoDocumentoAvulso->setDescricao('ADMINISTRATIVO');

        $manager->persist($generoDocumentoAvulso);

        $this->addReference(
            'GeneroDocumentoAvulso-'.$generoDocumentoAvulso->getNome(),
            $generoDocumentoAvulso
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
