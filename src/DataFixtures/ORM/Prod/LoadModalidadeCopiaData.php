<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeCopiaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeCopia;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class LoadModalidadeCopiaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeCopiaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $modalidades = $this->parameterBag->get('constantes.entidades.modalidade_copia.immutable.default');
        foreach ($modalidades as $modalidade) {
            $modalidadeCopia = new ModalidadeCopia();
            $modalidadeCopia->setValor($modalidade);
            $modalidadeCopia->setDescricao($modalidade);

            $manager->persist($modalidadeCopia);

            $this->addReference('ModalidadeCopia-'.$modalidadeCopia->getValor(), $modalidadeCopia);
        }

        // Flush database changes
        $manager->flush();
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

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
