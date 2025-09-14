<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadEtiquetaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta;

/**
 * Class LoadEtiquetaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadEtiquetaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $etiqueta = new Etiqueta();
        $etiqueta->setNome('MINUTA');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('COMPARTILHADA');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('OFÍCIO EM ELABORAÇÃO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('OFÍCIO REMETIDO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('OFÍCIO RESPONDIDO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('OFÍCIO VENCIDO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('REDISTRIBUÍDA');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('EM TRAMITAÇÃO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('SIGILOSO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('RELEVANTE');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('ACESSO RESTRITO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('LEMBRETE');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FFFFF0');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO', ModalidadeEtiqueta::class));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('ATENDIMENTO FORM');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#00BCD4');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('PARCELAMENTO FORM');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#FF9800');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('PACPREV FORM');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#00AAFF');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('FORMULÁRIO SALARIO MATERNIDADE RURAL DOSSIÊ PREVIDENCIÁRIO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#0F520F');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('ATENDIMENTO PGF');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#1E90FF');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('PARCELAMENTO PGF');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#0aa0b3');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('MATERNIDADE RURAL');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#258325');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('TRANSAÇÃO2024 ADESÃO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#0f0694');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('TRANSAÇÃO ADESÃO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#ff1900');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('TRANSAÇÃO2024 INFO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#2196f3');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-PROCESSO'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

        $etiqueta = new Etiqueta();
        $etiqueta->setNome('TRANSAÇÃO INFO');
        $etiqueta->setDescricao($etiqueta->getNome());
        $etiqueta->setCorHexadecimal('#ff1800');
        $etiqueta->setSistema(true);
        $etiqueta->setModalidadeEtiqueta($this->getReference('ModalidadeEtiqueta-TAREFA'));
        $this->addReference('Etiqueta-'.$etiqueta->getNome(), $etiqueta);
        $manager->persist($etiqueta);

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
