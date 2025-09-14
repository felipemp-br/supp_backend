<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadEspecieAtividadeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieAtividade;
use SuppCore\AdministrativoBackend\Entity\GeneroAtividade;

/**
 * Class LoadEspecieAtividadeData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieAtividadeData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS');
        $especieAtividade->setDescricao('PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('DEMANDAS, ANALISADAS');
        $especieAtividade->setDescricao('DEMANDAS, ANALISADAS');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('CIÊNCIA, APOSIÇÃO DE');
        $especieAtividade->setDescricao('CIÊNCIA, APOSIÇÃO DE');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('DOCUMENTO, APROVAÇÃO DE');
        $especieAtividade->setDescricao('DOCUMENTO, APROVAÇÃO DE');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, DESARQUIVADO/REATIVADO');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, DESARQUIVADO/REATIVADO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, ELIMINADO');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, ELIMINADO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, MANTIDO SOB GUARDA NO ARQUIVO CORRENTE');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, MANTIDO SOB GUARDA NO ARQUIVO CORRENTE');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, MARCADO COMO EXTRAVIADO');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, MARCADO COMO EXTRAVIADO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, RECOLHIDO AO ARQUIVO DEFINITIVO');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, RECOLHIDO AO ARQUIVO DEFINITIVO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, TRANSFERIDO AO ARQUIVO INTERMEDIÁRIO');
        $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, TRANSFERIDO AO ARQUIVO INTERMEDIÁRIO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, ELABORADA');
        $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, ELABORADA');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO');
        $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO');
        $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, ASSINADA');
        $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, ASSINADA');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
        );

        $especieAtividade = new EspecieAtividade();
        $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, PUBLICADA');
        $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, PUBLICADA');
        $especieAtividade->setGeneroAtividade(
            $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class)
        );

        $manager->persist($especieAtividade);

        $this->addReference(
            'EspecieAtividade-'.$especieAtividade->getNome(),
            $especieAtividade
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
        return ['prodexec'];
    }
}
