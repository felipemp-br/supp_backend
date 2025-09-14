<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadDocumentoIdentificadorData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDocumentoIdentificador;
use SuppCore\AdministrativoBackend\Entity\Pessoa;

/**
 * Class LoadDocumentoIdentificadorData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadDocumentoIdentificadorData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $documentoIdentificador = new DocumentoIdentificador();
        $documentoIdentificador->setNome('DOC-ID-ADMINISTRATIVO-1');
        $documentoIdentificador->setCodigoDocumento('DOC-ID-ADM1');
        $documentoIdentificador->setDataEmissao(null);
        $documentoIdentificador->setEmissorDocumento('SECRETARIA 1');
        $documentoIdentificador->setModalidadeDocumentoIdentificador(
            $this->getReference(
                'ModalidadeDocumentoIdentificador-CARTEIRA DE IDENTIDADE',
                ModalidadeDocumentoIdentificador::class
            )
        );
        $documentoIdentificador->setOrigemDados(null);
        $documentoIdentificador->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));

        $manager->persist($documentoIdentificador);

        $this->addReference(
            'DocumentoIdentificador-'.$documentoIdentificador->getNome(),
            $documentoIdentificador
        );

        $documentoIdentificador = new DocumentoIdentificador();
        $documentoIdentificador->setNome('DOC-ID-ADMINISTRATIVO-2');
        $documentoIdentificador->setCodigoDocumento('DOC-ID-ADM2');
        $documentoIdentificador->setDataEmissao(null);
        $documentoIdentificador->setEmissorDocumento('SECRETARIA 2');
        $documentoIdentificador->setModalidadeDocumentoIdentificador(
            $this->getReference(
                'ModalidadeDocumentoIdentificador-CARTEIRA NACIONAL DE HABILITAÇÃO',
                ModalidadeDocumentoIdentificador::class
            )
        );
        $documentoIdentificador->setOrigemDados(null);
        $documentoIdentificador->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));

        $manager->persist($documentoIdentificador);

        $this->addReference(
            'DocumentoIdentificador-'.$documentoIdentificador->getNome(),
            $documentoIdentificador
        );

        $documentoIdentificador = new DocumentoIdentificador();
        $documentoIdentificador->setNome('DOC-ID-JURÍDICO-1');
        $documentoIdentificador->setCodigoDocumento('DOC-ID-JUR1');
        $documentoIdentificador->setDataEmissao(null);
        $documentoIdentificador->setEmissorDocumento('JURÍDICO 1');
        $documentoIdentificador->setModalidadeDocumentoIdentificador(
            $this->getReference(
                'ModalidadeDocumentoIdentificador-TÍTULO DE ELEITOR',
                ModalidadeDocumentoIdentificador::class
            )
        );
        $documentoIdentificador->setOrigemDados(null);
        $documentoIdentificador->setPessoa($this->getReference('Pessoa-12312312355', Pessoa::class));

        $manager->persist($documentoIdentificador);

        $this->addReference(
            'DocumentoIdentificador-'.$documentoIdentificador->getNome(),
            $documentoIdentificador
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
        return ['test'];
    }
}
