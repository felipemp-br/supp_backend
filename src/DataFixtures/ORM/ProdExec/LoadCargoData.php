<?php

// DEV
declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadCargoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Cargo;

/**
 * Class LoadCargoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCargoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $cargo = new Cargo();
        $cargo->setNome('ALMIRANTE DA MARINHA DO BRASIL');
        $cargo->setDescricao('ALMIRANTE DA MARINHA DO BRASIL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('BRIGADEIRO DA FORÇA AÉREA BRASILEIRA');
        $cargo->setDescricao('BRIGADEIRO DA FORÇA AÉREA BRASILEIRA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('CHEFE DE GABINETE');
        $cargo->setDescricao('CHEFE DE GABINETE');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('CIDADÃO');
        $cargo->setDescricao('CIDADÃO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('CÔNSUL');
        $cargo->setDescricao('CÔNSUL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('CONSULESA');
        $cargo->setDescricao('CONSULESA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('COORDENADOR');
        $cargo->setDescricao('COORDENADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('COORDENADORA');
        $cargo->setDescricao('COORDENADORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('COORDENADORA-GERAL');
        $cargo->setDescricao('COORDENADORA-GERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('COORDENADOR-GERAL');
        $cargo->setDescricao('COORDENADOR-GERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DELEGADA DE POLÍCIA');
        $cargo->setDescricao('DELEGADA DE POLÍCIA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DELEGADA DE POLÍCIA FEDERAL');
        $cargo->setDescricao('DELEGADA DE POLÍCIA FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DELEGADO DE POLÍCIA');
        $cargo->setDescricao('DELEGADO DE POLÍCIA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DELEGADO DE POLÍCIA FEDERAL');
        $cargo->setDescricao('DELEGADO DE POLÍCIA FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DEPUTADA ESTADUAL');
        $cargo->setDescricao('DEPUTADA ESTADUAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DEPUTADA FEDERAL');
        $cargo->setDescricao('DEPUTADA FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DEPUTADO ESTADUAL');
        $cargo->setDescricao('DEPUTADO ESTADUAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DEPUTADO FEDERAL');
        $cargo->setDescricao('DEPUTADO FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DESEMBARGADOR DE JUSTIÇA');
        $cargo->setDescricao('DESEMBARGADOR DE JUSTIÇA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DESEMBARGADOR FEDERAL');
        $cargo->setDescricao('DESEMBARGADOR FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DESEMBARGADORA DE JUSTIÇA');
        $cargo->setDescricao('DESEMBARGADORA DE JUSTIÇA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DESEMBARGADORA FEDERAL');
        $cargo->setDescricao('DESEMBARGADORA FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DIRETOR');
        $cargo->setDescricao('DIRETOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('DIRETORA');
        $cargo->setDescricao('DIRETORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('EMBAIXADOR');
        $cargo->setDescricao('EMBAIXADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('EMBAIXADORA');
        $cargo->setDescricao('EMBAIXADORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('GENERAL DO EXÉRCITO BRASILEIRO');
        $cargo->setDescricao('GENERAL DO EXÉRCITO BRASILEIRO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('GOVERNADOR');
        $cargo->setDescricao('GOVERNADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('GOVERNADORA');
        $cargo->setDescricao('GOVERNADORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('JUIZ DE DIREITO');
        $cargo->setDescricao('JUIZ DE DIREITO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('JUIZ FEDERAL');
        $cargo->setDescricao('JUIZ FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('JUÍZA DE DIREITO');
        $cargo->setDescricao('JUÍZA DE DIREITO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('JUÍZA FEDERAL');
        $cargo->setDescricao('JUÍZA FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('MARECHAL DO EXÉRCITO BRASILEIRO');
        $cargo->setDescricao('MARECHAL DO EXÉRCITO BRASILEIRO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('MINISTRA DE ESTADO');
        $cargo->setDescricao('MINISTRA DE ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('MINISTRO DE ESTADO');
        $cargo->setDescricao('MINISTRO DE ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PREFEITA MUNICIPAL');
        $cargo->setDescricao('PREFEITA MUNICIPAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PREFEITO MUNICIPAL');
        $cargo->setDescricao('PREFEITO MUNICIPAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA');
        $cargo->setDescricao('PRESIDENTA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DA ASSEMBLEIA LEGISLATIVA');
        $cargo->setDescricao('PRESIDENTA DA ASSEMBLEIA LEGISLATIVA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DA CÂMARA LEGISLATIVA');
        $cargo->setDescricao('PRESIDENTA DA CÂMARA LEGISLATIVA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DA CÂMARA MUNICIPAL');
        $cargo->setDescricao('PRESIDENTA DA CÂMARA MUNICIPAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DA REPÚBLICA');
        $cargo->setDescricao('PRESIDENTA DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DO CONGRESSO NACIONAL');
        $cargo->setDescricao('PRESIDENTA DO CONGRESSO NACIONAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTA DO SUPREMO TRIBUNAL FEDERAL');
        $cargo->setDescricao('PRESIDENTA DO SUPREMO TRIBUNAL FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE');
        $cargo->setDescricao('PRESIDENTE');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DA ASSEMBLEIA LEGISLATIVA');
        $cargo->setDescricao('PRESIDENTE DA ASSEMBLEIA LEGISLATIVA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DA CÂMARA LEGISLATIVA');
        $cargo->setDescricao('PRESIDENTE DA CÂMARA LEGISLATIVA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DA CÂMARA MUNICIPAL');
        $cargo->setDescricao('PRESIDENTE DA CÂMARA MUNICIPAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DA REPÚBLICA');
        $cargo->setDescricao('PRESIDENTE DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DO CONGRESSO NACIONAL');
        $cargo->setDescricao('PRESIDENTE DO CONGRESSO NACIONAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PRESIDENTE DO SUPREMO TRIBUNAL FEDERAL');
        $cargo->setDescricao('PRESIDENTE DO SUPREMO TRIBUNAL FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DA REPÚBLICA');
        $cargo->setDescricao('PROCURADOR DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADORA DA REPÚBLICA');
        $cargo->setDescricao('PROCURADORA DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DO ESTADO');
        $cargo->setDescricao('PROCURADOR DO ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADORA DO ESTADO');
        $cargo->setDescricao('PROCURADORA DO ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROMOTOR DE JUSTIÇA');
        $cargo->setDescricao('PROMOTOR DE JUSTIÇA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROMOTORA DE JUSTIÇA');
        $cargo->setDescricao('PROMOTORA DE JUSTIÇA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('REITOR');
        $cargo->setDescricao('REITOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('REITORA');
        $cargo->setDescricao('REITORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIA');
        $cargo->setDescricao('SECRETÁRIA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIA DE ESTADO');
        $cargo->setDescricao('SECRETÁRIA DE ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO');
        $cargo->setDescricao('SECRETÁRIO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO DE ESTADO');
        $cargo->setDescricao('SECRETÁRIO DE ESTADO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO-ADJUNTO');
        $cargo->setDescricao('SECRETÁRIO-ADJUNTO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO-EXECUTIVO');
        $cargo->setDescricao('SECRETÁRIO-EXECUTIVO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO-EXECUTIVO ADJUNTO');
        $cargo->setDescricao('SECRETÁRIO-EXECUTIVO ADJUNTO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SECRETÁRIO-EXECUTIVO SUBSTITUTO');
        $cargo->setDescricao('SECRETÁRIO-EXECUTIVO SUBSTITUTO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SENADOR DA REPÚBLICA');
        $cargo->setDescricao('SENADOR DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SENADORA DA REPÚBLICA');
        $cargo->setDescricao('SENADORA DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SUPERINTENDENTE');
        $cargo->setDescricao('SUPERINTENDENTE');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VEREADOR');
        $cargo->setDescricao('VEREADOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VEREADORA');
        $cargo->setDescricao('VEREADORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VICE-PRESIDENTE');
        $cargo->setDescricao('VICE-PRESIDENTE');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VICE-PRESIDENTE DA REPÚBLICA');
        $cargo->setDescricao('VICE-PRESIDENTE DA REPÚBLICA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VICE-REITOR');
        $cargo->setDescricao('VICE-REITOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('VICE-REITORA');
        $cargo->setDescricao('VICE-REITORA');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('GERENTE');
        $cargo->setDescricao('GERENTE');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        // Fistures para carga no ambiente DEV
        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR FEDERAL');
        $cargo->setDescricao('PROCURADOR FEDERAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('ADVOGADO DA UNIÃO');
        $cargo->setDescricao('ADVOGADO DA UNIÃO');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DA FAZENDA NACIONAL');
        $cargo->setDescricao('PROCURADOR DA FAZENDA NACIONAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('PROCURADOR DO BANCO CENTRAL');
        $cargo->setDescricao('PROCURADOR DO BANCO CENTRAL');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

        $cargo = new Cargo();
        $cargo->setNome('SERVIDOR');
        $cargo->setDescricao('SERVIDOR');

        $manager->persist($cargo);

        $this->addReference('Cargo-'.$cargo->getNome(), $cargo);

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
