<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadPessoaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeGeneroPessoa;
use SuppCore\AdministrativoBackend\Entity\ModalidadeQualificacaoPessoa;
use SuppCore\AdministrativoBackend\Entity\Pessoa;

/**
 * Class LoadPessoaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadPessoaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pessoa = new Pessoa();
        $pessoa->setNome('ADVOCACIA-GERAL DA UNIÃO');
        $pessoa->setNumeroDocumentoPrincipal('26994558000123');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA JURÍDICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(true);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('UNIÃO FEDERAL');
        $pessoa->setNumeroDocumentoPrincipal('00000000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA JURÍDICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(true);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('INSTITUTO NACIONAL DO SEGURO SOCIAL - INSS');
        $pessoa->setNumeroDocumentoPrincipal('29979036000140');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA JURÍDICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(true);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('JOÃO USER');
        $pessoa->setNumeroDocumentoPrincipal('00000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA FÍSICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(false);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $pessoa->setModalidadeGeneroPessoa(
            $this->getReference('ModalidadeGeneroPessoa-DESCONHECIDO', ModalidadeGeneroPessoa::class)
        );
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('PEDRO USER');
        $pessoa->setNumeroDocumentoPrincipal('10000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA FÍSICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(false);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $pessoa->setModalidadeGeneroPessoa(
            $this->getReference('ModalidadeGeneroPessoa-DESCONHECIDO', ModalidadeGeneroPessoa::class)
        );
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('PAULO USER');
        $pessoa->setNumeroDocumentoPrincipal('20000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA FÍSICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(false);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $pessoa->setModalidadeGeneroPessoa(
            $this->getReference('ModalidadeGeneroPessoa-DESCONHECIDO', ModalidadeGeneroPessoa::class)
        );
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('LUCAS USER');
        $pessoa->setNumeroDocumentoPrincipal('30000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA FÍSICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(false);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $pessoa->setModalidadeGeneroPessoa(
            $this->getReference('ModalidadeGeneroPessoa-DESCONHECIDO', ModalidadeGeneroPessoa::class)
        );
        $manager->persist($pessoa);

        $pessoa = new Pessoa();
        $pessoa->setNome('MATEUS USER');
        $pessoa->setNumeroDocumentoPrincipal('40000000001');
        $pessoa->setModalidadeQualificacaoPessoa(
            $this->getReference('ModalidadeQualificacaoPessoa-PESSOA FÍSICA', ModalidadeQualificacaoPessoa::class)
        );
        $pessoa->setPessoaValidada(false);
        $this->addReference('Pessoa-'.$pessoa->getNumeroDocumentoPrincipal(), $pessoa);
        $pessoa->setModalidadeGeneroPessoa(
            $this->getReference('ModalidadeGeneroPessoa-DESCONHECIDO', ModalidadeGeneroPessoa::class)
        );
        $manager->persist($pessoa);

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
        return ['dev', 'test'];
    }
}
