<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEspecieAtividadeData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

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
        $generoAtividadeAdministrativo = $manager
            ->createQuery(
                "
                SELECT ga 
                FROM SuppCore\AdministrativoBackend\Entity\GeneroAtividade ga 
                WHERE ga.nome = 'ADMINISTRATIVO'"
            )
            ->getOneOrNullResult() ?: $this->getReference('GeneroAtividade-ADMINISTRATIVO', GeneroAtividade::class);
        $generoAtividadeArquivistico = $manager
            ->createQuery(
                "
                SELECT ga 
                FROM SuppCore\AdministrativoBackend\Entity\GeneroAtividade ga 
                WHERE ga.nome = 'ARQUIVÍSTICO'"
            )
            ->getOneOrNullResult() ?: $this->getReference('GeneroAtividade-ARQUIVÍSTICO', GeneroAtividade::class);


        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS');
            $especieAtividade->setDescricao('PROVIDÊNCIAS ADMINISTRATIVAS, ADOTADAS');
            $especieAtividade->setGeneroAtividade(
                $generoAtividadeAdministrativo
            );

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'DEMANDAS, ANALISADAS'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('DEMANDAS, ANALISADAS');
            $especieAtividade->setDescricao('DEMANDAS, ANALISADAS');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'CIÊNCIA, APOSIÇÃO DE'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('CIÊNCIA, APOSIÇÃO DE');
            $especieAtividade->setDescricao('CIÊNCIA, APOSIÇÃO DE');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'DOCUMENTO, APROVAÇÃO DE'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('DOCUMENTO, APROVAÇÃO DE');
            $especieAtividade->setDescricao('DOCUMENTO, APROVAÇÃO DE');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, DESARQUIVADO/REATIVADO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, DESARQUIVADO/REATIVADO');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, DESARQUIVADO/REATIVADO');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, ELIMINADO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, ELIMINADO');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, ELIMINADO');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, MANTIDO SOB GUARDA NO ARQUIVO CORRENTE'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, MANTIDO SOB GUARDA NO ARQUIVO CORRENTE');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, MANTIDO SOB GUARDA NO ARQUIVO CORRENTE');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, MARCADO COMO EXTRAVIADO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, MARCADO COMO EXTRAVIADO');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, MARCADO COMO EXTRAVIADO');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, RECOLHIDO AO ARQUIVO DEFINITIVO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, RECOLHIDO AO ARQUIVO DEFINITIVO');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, RECOLHIDO AO ARQUIVO DEFINITIVO');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'PROCESSO/DOCUMENTO AVULSO, TRANSFERIDO AO ARQUIVO INTERMEDIÁRIO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('PROCESSO/DOCUMENTO AVULSO, TRANSFERIDO AO ARQUIVO INTERMEDIÁRIO');
            $especieAtividade->setDescricao('PROCESSO/DOCUMENTO AVULSO, TRANSFERIDO AO ARQUIVO INTERMEDIÁRIO');
            $especieAtividade->setGeneroAtividade($generoAtividadeArquivistico);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'MINUTA DE ATO NORMATIVO, ELABORADA'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, ELABORADA');
            $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, ELABORADA');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO');
            $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, REVISADA COM APROVAÇÃO');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO');
            $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, REVISADA COM REJEIÇÃO');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'MINUTA DE ATO NORMATIVO, ASSINADA'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, ASSINADA');
            $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, ASSINADA');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'MINUTA DE ATO NORMATIVO, PUBLICADA'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('MINUTA DE ATO NORMATIVO, PUBLICADA');
            $especieAtividade->setDescricao('MINUTA DE ATO NORMATIVO, PUBLICADA');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'DEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('DEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO');
            $especieAtividade->setDescricao('DEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT ea 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieAtividade ea 
                WHERE ea.nome = 'INDEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO'"
                )
                ->getOneOrNullResult()) {
            $especieAtividade = new EspecieAtividade();
            $especieAtividade->setNome('INDEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO');
            $especieAtividade->setDescricao('INDEFERIMENTO. SOLICITAÇÃO PROTOCOLO EXTERNO');
            $especieAtividade->setGeneroAtividade($generoAtividadeAdministrativo);

            $manager->persist($especieAtividade);

            $this->addReference(
                'EspecieAtividade-'.$especieAtividade->getNome(),
                $especieAtividade
            );
        }

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
