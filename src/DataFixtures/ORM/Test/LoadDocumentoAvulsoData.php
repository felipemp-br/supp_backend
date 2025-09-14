<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadDocumentoAvulsoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\EspecieDocumentoAvulso;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadDocumentoAvulsoData.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadDocumentoAvulsoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $documentoAvulso = new DocumentoAvulso();

        $documentoAvulso->setDataHoraConclusaoPrazo(null);
        $documentoAvulso->setDataHoraInicioPrazo(date_create('now'));
        $documentoAvulso->setDataHoraEncerramento(null);
        $documentoAvulso->setDataHoraFinalPrazo(date_create('2022-06-15 17:00:00'));
        $documentoAvulso->setDataHoraLeitura(null);
        $documentoAvulso->setDataHoraReiteracao(null);
        $documentoAvulso->setDataHoraRemessa(null);
        $documentoAvulso->setDataHoraResposta(null);
        $documentoAvulso->setMecanismoRemessa(null);
        $documentoAvulso->setObservacao(null);
        $documentoAvulso->setPostIt('TESTE_1');
        $documentoAvulso->setUrgente(false);

        $documentoAvulso->setDocumentoAvulsoOrigem(null);
        $documentoAvulso->setDocumentoResposta(null);
        $documentoAvulso->setProcessoDestino(null);
        $documentoAvulso->setPessoaDestino(null);
        $documentoAvulso->setSetorDestino(null);
        $documentoAvulso->setTarefaOrigem(null);
        $documentoAvulso->setUsuarioRemessa(null);
        $documentoAvulso->setUsuarioResposta(null);

        $documentoAvulso->setDocumentoRemessa(
            $this->getReference('Documento-MODELO DESPACHO DE APROVAÇÃO', Documento::class)
        );
        $documentoAvulso->setEspecieDocumentoAvulso(
            $this->getReference('EspecieDocumentoAvulso-SOLICITAÇÃO', EspecieDocumentoAvulso::class)
        );
        $documentoAvulso->setModelo($this->getReference('Modelo-DESPACHO EM BRANCO', Modelo::class));
        $documentoAvulso->setProcesso($this->getReference('Processo-00100000001202088', Processo::class));
        $documentoAvulso->setSetorOrigem($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $documentoAvulso->setSetorResponsavel($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $documentoAvulso->setUsuarioResponsavel($this->getReference('Usuario-00000000004', Usuario::class));
        $documentoAvulso->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

        // Persist entity
        $manager->persist($documentoAvulso);

        // Create reference for later usage
        $this->addReference('DocumentoAvulso-'.$documentoAvulso->getPostIt(), $documentoAvulso);

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
