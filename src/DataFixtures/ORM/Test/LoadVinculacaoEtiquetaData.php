<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadVinculacaoEtiquetaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;

/**
 * Class LoadVinculacaoEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoEtiqueta = new VinculacaoEtiqueta();
        $vinculacaoEtiqueta->setEtiqueta($this->getReference('Etiqueta-TESTE', Etiqueta::class));
        $vinculacaoEtiqueta->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $vinculacaoEtiqueta->setConteudo('TESTE');
        $vinculacaoEtiqueta->setLabel('TESTE');
        $vinculacaoEtiqueta->setPrivada(false);
        $vinculacaoEtiqueta->setSugestao(false);

        // Persist entity
        $manager->persist($vinculacaoEtiqueta);

        $vinculacaoEtiqueta = new VinculacaoEtiqueta();
        $vinculacaoEtiqueta->setEtiqueta($this->getReference('Etiqueta-TESTE', Etiqueta::class));
        $vinculacaoEtiqueta->setConteudo('TESTE 2');
        $vinculacaoEtiqueta->setLabel('TESTE 2');
        $vinculacaoEtiqueta->setPrivada(false);
        $vinculacaoEtiqueta->setSugestao(false);
        $vinculacaoEtiqueta->setUsuario($this->getReference('Usuario-00000000004'));

        // Persist entity
        $manager->persist($vinculacaoEtiqueta);

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
