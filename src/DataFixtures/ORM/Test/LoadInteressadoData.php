<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadInteressadoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * Class LoadInteressadoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadInteressadoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $interessado = new Interessado();
        $interessado->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));
        $interessado->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $interessado->setOrigemDados($this->getReference('OrigemDados-FONTE_DADOS_1', OrigemDados::class));
        $interessado->setModalidadeInteressado(
            $this->getReference('ModalidadeInteressado-TERCEIRO', ModalidadeInteressado::class)
        );

        $manager->persist($interessado);

        $this->addReference(
            'Interessado-'.$interessado->getPessoa()->getNumeroDocumentoPrincipal(),
            $interessado
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
