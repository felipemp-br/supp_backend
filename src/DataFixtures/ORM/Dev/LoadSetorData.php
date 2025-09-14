<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadSetorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\GeneroSetor;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class LoadSetorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadSetorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $setor = new Setor();
        $setor->setNome('ADVOCACIA-GERAL DA UNIÃO');
        $setor->setSigla('AGU-SEDE');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));

        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));
        $setor->setModalidadeOrgaoCentral(
            $this->getReference('ModalidadeOrgaoCentral-AGU', ModalidadeOrgaoCentral::class)
        );

        $manager->persist($setor);

        $this->addReference(
            'Unidade-'.$setor->getNome(),
            $setor
        );

        $manager->flush();

        $setor->setUnidade($this->getReference('Unidade-'.$setor->getNome(), Setor::class));

        $manager->persist($setor);

        $manager->flush();

        $setor = new Setor();
        $setor->setNome('PROTOCOLO');
        $setor->setSigla('PROT');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-PROTOCOLO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('ARQUIVO');
        $setor->setSigla('ARQU');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-ARQUIVO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('PROCURADORIA-GERAL FEDERAL');
        $setor->setSigla('PGF-SEDE');
        $setor->setPrefixoNUP('00407');
        $setor->setSequenciaInicialNUP(1);
        $setor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));
        $setor->setUnidade($setor);
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));
        $setor->setModalidadeOrgaoCentral(
            $this->getReference('ModalidadeOrgaoCentral-PGF', ModalidadeOrgaoCentral::class)
        );

        $manager->persist($setor);

        $this->addReference(
            'Unidade-'.$setor->getNome(),
            $setor
        );

        $manager->flush();

        $setor->setUnidade($this->getReference('Unidade-'.$setor->getNome(), Setor::class));

        $manager->persist($setor);

        $manager->flush();

        $setor = new Setor();
        $setor->setNome('PROTOCOLO');
        $setor->setSigla('PROT');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-PROTOCOLO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('ARQUIVO');
        $setor->setSigla('ARQU');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-ARQUIVO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL FEDERAL', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('CONSULTORIA-GERAL DA UNIÃO');
        $setor->setSigla('CGU-SEDE');
        $setor->setPrefixoNUP('00407');
        $setor->setSequenciaInicialNUP(1);
        $setor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));
        $setor->setUnidade($setor);
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));
        $setor->setModalidadeOrgaoCentral(
            $this->getReference('ModalidadeOrgaoCentral-CGU', ModalidadeOrgaoCentral::class)
        );

        $manager->persist($setor);

        $this->addReference(
            'Unidade-'.$setor->getNome(),
            $setor
        );

        $manager->flush();

        $setor->setUnidade($this->getReference('Unidade-'.$setor->getNome(), Setor::class));

        $manager->persist($setor);

        $manager->flush();

        $setor = new Setor();
        $setor->setNome('PROTOCOLO');
        $setor->setSigla('PROT');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-PROTOCOLO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('ARQUIVO');
        $setor->setSigla('ARQU');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-ARQUIVO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA-GERAL DE ADMINISTRAÇÃO');
        $setor->setSigla('SGA-SEDE');
        $setor->setPrefixoNUP('00407');
        $setor->setSequenciaInicialNUP(1);
        $setor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));
        $setor->setUnidade($setor);
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));
        $setor->setModalidadeOrgaoCentral(
            $this->getReference('ModalidadeOrgaoCentral-SGA', ModalidadeOrgaoCentral::class)
        );

        $manager->persist($setor);

        $this->addReference(
            'Unidade-'.$setor->getNome(),
            $setor
        );

        $manager->flush();

        $setor->setUnidade($this->getReference('Unidade-'.$setor->getNome(), Setor::class));

        $manager->persist($setor);

        $manager->flush();

        $setor = new Setor();
        $setor->setNome('PROTOCOLO');
        $setor->setSigla('PROT');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-PROTOCOLO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('ARQUIVO');
        $setor->setSigla('ARQU');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-ARQUIVO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-SECRETARIA-GERAL DE ADMINISTRAÇÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('PROCURADORIA-GERAL DA UNIÃO');
        $setor->setSigla('PGU-SEDE');
        $setor->setPrefixoNUP('00407');
        $setor->setSequenciaInicialNUP(1);
        $setor->setGeneroSetor($this->getReference('GeneroSetor-ADMINISTRATIVO', GeneroSetor::class));
        $setor->setUnidade($setor);
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));
        $setor->setModalidadeOrgaoCentral(
            $this->getReference('ModalidadeOrgaoCentral-PGU', ModalidadeOrgaoCentral::class)
        );

        $manager->persist($setor);

        $this->addReference(
            'Unidade-'.$setor->getNome(),
            $setor
        );

        $manager->flush();

        $setor->setUnidade($this->getReference('Unidade-'.$setor->getNome(), Setor::class));

        $manager->persist($setor);

        $manager->flush();

        $setor = new Setor();
        $setor->setNome('PROTOCOLO');
        $setor->setSigla('PROT');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-PROTOCOLO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('ARQUIVO');
        $setor->setSigla('ARQU');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-ARQUIVO', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
        );

        $setor = new Setor();
        $setor->setNome('SECRETARIA');
        $setor->setSigla('SECR');
        $setor->setPrefixoNUP('00400');
        $setor->setSequenciaInicialNUP(1);
        $setor->setEspecieSetor($this->getReference('EspecieSetor-SECRETARIA', EspecieSetor::class));
        $setor->setUnidade($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setParent($this->getReference('Unidade-PROCURADORIA-GERAL DA UNIÃO', Setor::class));
        $setor->setMunicipio($this->getReference('Municipio-BRASÍLIA-DF', Municipio::class));

        $manager->persist($setor);

        $this->addReference(
            'Setor-'.$setor->getNome().'-'.$setor->getUnidade()->getSigla(),
            $setor
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
        return 4;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['dev', 'test'];
    }
}
