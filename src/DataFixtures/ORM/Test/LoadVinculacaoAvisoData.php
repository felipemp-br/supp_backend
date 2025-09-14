<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Aviso;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoAviso;

/**
 * Class LoadVinculacaoAvisoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadVinculacaoAvisoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $vinculacaoAviso = new VinculacaoAviso();
        $vinculacaoAviso->setAviso($this->getReference('Aviso-NOME_1', Aviso::class));
        $vinculacaoAviso->setEspecieSetor(null);
        $vinculacaoAviso->setModalidadeOrgaoCentral(null);
        $vinculacaoAviso->setSetor(null);
        $vinculacaoAviso->setUnidade(null);
        $vinculacaoAviso->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($vinculacaoAviso);

        $this->addReference('VinculacaoAviso-'.$vinculacaoAviso->getAviso()->getNome(), $vinculacaoAviso);

        $vinculacaoAviso = new VinculacaoAviso();
        $vinculacaoAviso->setAviso($this->getReference('Aviso-NOME_2', Aviso::class));
        $vinculacaoAviso->setEspecieSetor(null);
        $vinculacaoAviso->setModalidadeOrgaoCentral(null);
        $vinculacaoAviso->setSetor(null);
        $vinculacaoAviso->setUnidade(null);
        $vinculacaoAviso->setUsuario($this->getReference('Usuario-00000000004', Usuario::class));

        $manager->persist($vinculacaoAviso);

        $this->addReference('VinculacaoAviso-'.$vinculacaoAviso->getAviso()->getNome(), $vinculacaoAviso);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 7;
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['test'];
    }
}
