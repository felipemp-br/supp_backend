<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadTipoDocumentoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumento;
use SuppCore\AdministrativoBackend\Entity\TipoDocumento;

/**
 * Class LoadTipoDocumentoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoDocumentoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ALVAR');
        $tipoDocumento->setNome('ALVARÁ');
        $tipoDocumento->setDescricao('ALVARÁ');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('APOST');
        $tipoDocumento->setNome('APOSTILA');
        $tipoDocumento->setDescricao('APOSTILA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ATA');
        $tipoDocumento->setNome('ATA');
        $tipoDocumento->setDescricao('ATA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ATEST');
        $tipoDocumento->setNome('ATESTADO');
        $tipoDocumento->setDescricao('ATESTADO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('AUTO');
        $tipoDocumento->setNome('AUTO');
        $tipoDocumento->setDescricao('AUTO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('AVISO');
        $tipoDocumento->setNome('AVISO');
        $tipoDocumento->setDescricao('AVISO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('BOLET');
        $tipoDocumento->setNome('BOLETIM');
        $tipoDocumento->setDescricao('BOLETIM');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CARTA');
        $tipoDocumento->setNome('CARTA');
        $tipoDocumento->setDescricao('CARTA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CERTI');
        $tipoDocumento->setNome('CERTIDÃO');
        $tipoDocumento->setDescricao('CERTIDÃO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CIRCU');
        $tipoDocumento->setNome('CIRCULAR');
        $tipoDocumento->setDescricao('CIRCULAR');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CONTR');
        $tipoDocumento->setNome('CONTRATO');
        $tipoDocumento->setDescricao('CONTRATO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CONVE');
        $tipoDocumento->setNome('CONVÊNIO');
        $tipoDocumento->setDescricao('CONVÊNIO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CONVI');
        $tipoDocumento->setNome('CONVITE');
        $tipoDocumento->setDescricao('CONVITE');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('CONVO');
        $tipoDocumento->setNome('CONVOCAÇÃO');
        $tipoDocumento->setDescricao('CONVOCAÇÃO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('DECLA');
        $tipoDocumento->setNome('DECLARAÇÃO');
        $tipoDocumento->setDescricao('DECLARAÇÃO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('DECRE');
        $tipoDocumento->setNome('DECRETO');
        $tipoDocumento->setDescricao('DECRETO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('DELIB');
        $tipoDocumento->setNome('DELIBERAÇÃO');
        $tipoDocumento->setDescricao('DELIBERAÇÃO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('DESPA');
        $tipoDocumento->setNome('DESPACHO');
        $tipoDocumento->setDescricao('DESPACHO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('EDITA');
        $tipoDocumento->setNome('EDITAL');
        $tipoDocumento->setDescricao('EDITAL');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('EMAIL');
        $tipoDocumento->setNome('E-MAIL');
        $tipoDocumento->setDescricao('E-MAIL');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ESTAT');
        $tipoDocumento->setNome('ESTATUTO');
        $tipoDocumento->setDescricao('ESTATUTO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('EXPOS');
        $tipoDocumento->setNome('EXPOSIÇÃO DE MOTIVOS');
        $tipoDocumento->setDescricao('EXPOSIÇÃO DE MOTIVOS');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('FAX');
        $tipoDocumento->setNome('FAX');
        $tipoDocumento->setDescricao('FAX');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('GUIA');
        $tipoDocumento->setNome('GUIA');
        $tipoDocumento->setDescricao('GUIA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('INSTR');
        $tipoDocumento->setNome('INSTRUÇÃO NORMATIVA');
        $tipoDocumento->setDescricao('INSTRUÇÃO NORMATIVA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('MEMOR');
        $tipoDocumento->setNome('MEMORANDO');
        $tipoDocumento->setDescricao('MEMORANDO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('MENSAGEM');
        $tipoDocumento->setNome('MENSAGEM');
        $tipoDocumento->setDescricao('MENSAGEM');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('OFICIO');
        $tipoDocumento->setNome('OFÍCIO');
        $tipoDocumento->setDescricao('OFÍCIO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ORDEM');
        $tipoDocumento->setNome('ORDEM DE SERVIÇO');
        $tipoDocumento->setDescricao('ORDEM DE SERVIÇO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('PORTA');
        $tipoDocumento->setNome('PORTARIA');
        $tipoDocumento->setDescricao('PORTARIA');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('RELAT');
        $tipoDocumento->setNome('RELATÓRIO');
        $tipoDocumento->setDescricao('RELATÓRIO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('REQUE');
        $tipoDocumento->setNome('REQUERIMENTO');
        $tipoDocumento->setDescricao('REQUERIMENTO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('RESOL');
        $tipoDocumento->setNome('RESOLUÇÃO');
        $tipoDocumento->setDescricao('RESOLUÇÃO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ARDIG');
        $tipoDocumento->setNome('AR DIGITAL');
        $tipoDocumento->setDescricao('AR DIGITAL');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('ANEXO');
        $tipoDocumento->setNome('ANEXO');
        $tipoDocumento->setDescricao('ANEXO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('REPO');
        $tipoDocumento->setNome('REPOSITÓRIO');
        $tipoDocumento->setDescricao('REPOSITÓRIO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('OUTRO');
        $tipoDocumento->setNome('OUTROS');
        $tipoDocumento->setDescricao('OUTROS');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setEspecieDocumento(
            $this->getReference('EspecieDocumento-ADMINISTRATIVO', EspecieDocumento::class)
        );
        $tipoDocumento->setSigla('RECI');
        $tipoDocumento->setNome('RECIBO');
        $tipoDocumento->setDescricao('RECIBO');
        $manager->persist($tipoDocumento);
        $this->addReference('TipoDocumento-'.$tipoDocumento->getNome(), $tipoDocumento);

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
        return 3;
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
