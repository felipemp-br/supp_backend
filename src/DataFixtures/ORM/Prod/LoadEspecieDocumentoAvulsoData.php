<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEspecieDocumentoAvulsoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumentoAvulso;

/**
 * Class LoadEspecieDocumentoAvulsoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieDocumentoAvulsoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieDocumentoAvulso = new EspecieDocumentoAvulso();
        $especieDocumentoAvulso->setNome('NOTIFICAÇÃO');
        $especieDocumentoAvulso->setDescricao('NOTIFICAÇÃO');
        $especieDocumentoAvulso->setGeneroDocumentoAvulso(
            $this->getReference('GeneroDocumentoAvulso-ADMINISTRATIVO', GeneroDocumentoAvulso::class)
        );

        $manager->persist($especieDocumentoAvulso);

        $this->addReference(
            'EspecieDocumentoAvulso-'.$especieDocumentoAvulso->getNome(),
            $especieDocumentoAvulso
        );

        $especieDocumentoAvulso = new EspecieDocumentoAvulso();
        $especieDocumentoAvulso->setNome('SOLICITAÇÃO');
        $especieDocumentoAvulso->setDescricao('SOLICITAÇÃO');
        $especieDocumentoAvulso->setGeneroDocumentoAvulso(
            $this->getReference('GeneroDocumentoAvulso-ADMINISTRATIVO', GeneroDocumentoAvulso::class)
        );

        $manager->persist($especieDocumentoAvulso);

        $this->addReference(
            'EspecieDocumentoAvulso-'.$especieDocumentoAvulso->getNome(),
            $especieDocumentoAvulso
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
        return 2;
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
