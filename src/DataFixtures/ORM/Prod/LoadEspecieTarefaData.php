<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEspecieTarefaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa;

/**
 * Class LoadEspecieTarefaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEspecieTarefaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $generoTarefaAdministrativo = $manager
            ->createQuery(
                "
                SELECT gt 
                FROM SuppCore\AdministrativoBackend\Entity\GeneroTarefa gt 
                WHERE gt.nome = 'ADMINISTRATIVO'"
            )
            ->getOneOrNullResult() ?: $this->getReference('GeneroTarefa-ADMINISTRATIVO', GeneroTarefa::class);


        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS');
            $especieTarefa->setDescricao('ADOTAR PROVIDÊNCIAS ADMINISTRATIVAS');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ANALISAR DEMANDAS'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ANALISAR DEMANDAS');
            $especieTarefa->setDescricao('ANALISAR DEMANDAS');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'APROVAR DOCUMENTO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('APROVAR DOCUMENTO');
            $especieTarefa->setDescricao('APROVAR DOCUMENTO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'PARTICIPAR DE REUNIÃO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('PARTICIPAR DE REUNIÃO');
            $especieTarefa->setDescricao('PARTICIPAR DE REUNIÃO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);
            $especieTarefa->setEvento(true);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'DESARQUIVAR/REATIVAR PROCESSO/DOCUMENTO AVULSO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('DESARQUIVAR/REATIVAR PROCESSO/DOCUMENTO AVULSO');
            $especieTarefa->setDescricao('DESARQUIVAR/REATIVAR PROCESSO/DOCUMENTO AVULSO');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ELIMINAR PROCESSO/DOCUMENTO AVULSO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ELIMINAR PROCESSO/DOCUMENTO AVULSO');
            $especieTarefa->setDescricao('ELIMINAR PROCESSO/DOCUMENTO AVULSO');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'MANTER SOB GUARDA TEMPORÁRIA NO ARQUIVO CORRENTE'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('MANTER SOB GUARDA TEMPORÁRIA NO ARQUIVO CORRENTE');
            $especieTarefa->setDescricao('MANTER SOB GUARDA TEMPORÁRIA NO ARQUIVO CORRENTE');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'MARCAR PROCESSO/DOCUMENTO AVULSO COMO EXTRAVIADO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('MARCAR PROCESSO/DOCUMENTO AVULSO COMO EXTRAVIADO');
            $especieTarefa->setDescricao('MARCAR PROCESSO/DOCUMENTO AVULSO COMO EXTRAVIADO');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'RECOLHER PROCESSO/DOCUMENTO AVULSO AO ARQUIVO DEFINITIVO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('RECOLHER PROCESSO/DOCUMENTO AVULSO AO ARQUIVO DEFINITIVO');
            $especieTarefa->setDescricao('RECOLHER PROCESSO/DOCUMENTO AVULSO AO ARQUIVO DEFINITIVO');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'TRANSFERIR PROCESSO/DOCUMENTO AVULSO AO ARQUIVO INTERMEDIÁRIO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('TRANSFERIR PROCESSO/DOCUMENTO AVULSO AO ARQUIVO INTERMEDIÁRIO');
            $especieTarefa->setDescricao('TRANSFERIR PROCESSO/DOCUMENTO AVULSO AO ARQUIVO INTERMEDIÁRIO');
            $especieTarefa->setGeneroTarefa($this->getReference('GeneroTarefa-ARQUIVÍSTICO', GeneroTarefa::class));

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ELABORAR MINUTA DE ATO NORMATIVO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ELABORAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setDescricao('ELABORAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'REVISAR MINUTA DE ATO NORMATIVO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('REVISAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setDescricao('REVISAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ASSINAR MINUTA DE ATO NORMATIVO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ASSINAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setDescricao('ASSINAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'PUBLICAR MINUTA DE ATO NORMATIVO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('PUBLICAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setDescricao('PUBLICAR MINUTA DE ATO NORMATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'MANTER SOB GUARDA NO ARQUIVO CORRENTE'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('MANTER SOB GUARDA NO ARQUIVO CORRENTE');
            $especieTarefa->setDescricao('MANTER SOB GUARDA NO ARQUIVO CORRENTE');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'DAR ANDAMENTO AO PROCESSO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('DAR ANDAMENTO AO PROCESSO');
            $especieTarefa->setDescricao('ADMINISTRATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);
            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'RESPONDER OFÍCIO REITERADO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('RESPONDER OFÍCIO REITERADO');
            $especieTarefa->setDescricao('ADMINISTRATIVO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);
            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'REVISAR DOCUMENTO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('REVISAR DOCUMENTO');
            $especieTarefa->setDescricao('REVISAR DOCUMENTO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
            );
        }

        if (null === $manager
                ->createQuery(
                    "
                SELECT et 
                FROM SuppCore\AdministrativoBackend\Entity\EspecieTarefa et 
                WHERE et.nome = 'ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO'"
                )
                ->getOneOrNullResult()) {
            $especieTarefa = new EspecieTarefa();
            $especieTarefa->setNome('ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO');
            $especieTarefa->setDescricao('ANÁLISE SOLICITAÇÃO AUTOMATIZADA PROTOCOLO EXTERNO');
            $especieTarefa->setGeneroTarefa($generoTarefaAdministrativo);

            $manager->persist($especieTarefa);

            $this->addReference(
                'EspecieTarefa-'.$especieTarefa->getNome(),
                $especieTarefa
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
