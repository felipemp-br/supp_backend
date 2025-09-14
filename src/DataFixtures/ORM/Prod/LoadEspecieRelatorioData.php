<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEspecieRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelatorio;
use SuppCore\AdministrativoBackend\Entity\GeneroRelatorio;

/**
 * Class LoadEspecieRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieRelatorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Adicionar novas espécies no final da listagem

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('TAREFAS');
        $especieRelatorio->setDescricao('TAREFAS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('ATIVIDADES');
        $especieRelatorio->setDescricao('ATIVIDADES');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('COMUNICAÇÕES');
        $especieRelatorio->setDescricao('COMUNICAÇÕES');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('PROCESSOS/DOCUMENTOS AVULSOS');
        $especieRelatorio->setDescricao('PROCESSOS/DOCUMENTOS AVULSOS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('TABELAS');
        $especieRelatorio->setDescricao('TABELAS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-SISTEMA', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('TRAMITAÇÕES');
        $especieRelatorio->setDescricao('TRAMITAÇÕES');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('FLUXO DE TRABALHO');
        $especieRelatorio->setDescricao('FLUXO DE TRABALHO');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-GERENCIAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('DÍVIDA ATIVA');
        $especieRelatorio->setDescricao('DÍVIDA ATIVA');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('GESTÃO DA DÍVIDA');
        $especieRelatorio->setDescricao('GESTÃO DA DÍVIDA');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-GERENCIAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('DISTRIBUIÇÃO');
        $especieRelatorio->setDescricao('DISTRIBUIÇÃO');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-GERENCIAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('RECURSOS HUMANOS');
        $especieRelatorio->setDescricao('RECURSOS HUMANOS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-GERENCIAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('INTIMAÇÕES');
        $especieRelatorio->setDescricao('INTIMAÇÕES');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-OPERACIONAL', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('USUÁRIOS');
        $especieRelatorio->setDescricao('USUÁRIOS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-SISTEMA', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('PLANO DE CLASSIFICAÇÃO');
        $especieRelatorio->setDescricao('PLANO DE CLASSIFICAÇÃO');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-ARQUIVÍSTICO', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('TABELA DE TEMPORALIDADE');
        $especieRelatorio->setDescricao('TABELA DE TEMPORALIDADE');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-ARQUIVÍSTICO', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('FLUXO DE TRAMITAÇÕES');
        $especieRelatorio->setDescricao('FLUXO DE TRAMITAÇÕES');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-ARQUIVÍSTICO', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('FLUXO DE TAREFAS');
        $especieRelatorio->setDescricao('FLUXO DE TAREFAS');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-ARQUIVÍSTICO', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
        );

        $especieRelatorio = new EspecieRelatorio();
        $especieRelatorio->setNome('HISTÓRICO');
        $especieRelatorio->setDescricao('HISTÓRICO');
        $especieRelatorio->setGeneroRelatorio(
            $this->getReference('GeneroRelatorio-ARQUIVÍSTICO', GeneroRelatorio::class)
        );
        $manager->persist($especieRelatorio);
        $this->addReference(
            'EspecieRelatorio-'.$especieRelatorio->getNome(),
            $especieRelatorio
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
