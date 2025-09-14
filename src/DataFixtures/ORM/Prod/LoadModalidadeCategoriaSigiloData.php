<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeCategoriaSigiloData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeCategoriaSigilo;

/**
 * Class LoadModalidadeCategoriaSigiloData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeCategoriaSigiloData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('AGRICULTURA, EXTRATIVISMO E PESCA');
        $modalidadeCategoriaSigilo->setDescricao('AGRICULTURA, EXTRATIVISMO E PESCA');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('CIÊNCIA, INFORMAÇÃO E COMUNICAÇÃO');
        $modalidadeCategoriaSigilo->setDescricao('CIÊNCIA, INFORMAÇÃO E COMUNICAÇÃO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('COMÉRCIO, SERVIÇOS E TURISMO');
        $modalidadeCategoriaSigilo->setDescricao('COMÉRCIO, SERVIÇOS E TURISMO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('CULTURA, LAZER E ESPORTE');
        $modalidadeCategoriaSigilo->setDescricao('CULTURA, LAZER E ESPORTE');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('DEFESA E SEGURANÇA');
        $modalidadeCategoriaSigilo->setDescricao('DEFESA E SEGURANÇA');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('ECONOMIA E FINANÇAS');
        $modalidadeCategoriaSigilo->setDescricao('ECONOMIA E FINANÇAS');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('EDUCAÇÃO');
        $modalidadeCategoriaSigilo->setDescricao('EDUCAÇÃO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('GOVERNO E POLÍTICA');
        $modalidadeCategoriaSigilo->setDescricao('GOVERNO E POLÍTICA');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('HABITAÇÃO, SANEAMENTO E URBANISMO');
        $modalidadeCategoriaSigilo->setDescricao('HABITAÇÃO, SANEAMENTO E URBANISMO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('INDÚSTRIA');
        $modalidadeCategoriaSigilo->setDescricao('INDÚSTRIA');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('JUSTIÇA E LEGISLAÇÃO');
        $modalidadeCategoriaSigilo->setDescricao('JUSTIÇA E LEGISLAÇÃO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('MEIO AMBIENTE');
        $modalidadeCategoriaSigilo->setDescricao('MEIO AMBIENTE');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('PESSOA, FAMÍLIA E SOCIEDADE');
        $modalidadeCategoriaSigilo->setDescricao('PESSOA, FAMÍLIA E SOCIEDADE');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('RELAÇÕES INTERNACIONAIS');
        $modalidadeCategoriaSigilo->setDescricao('RELAÇÕES INTERNACIONAIS');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('SAÚDE');
        $modalidadeCategoriaSigilo->setDescricao('SAÚDE');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('TRABALHO');
        $modalidadeCategoriaSigilo->setDescricao('TRABALHO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

        $modalidadeCategoriaSigilo = new ModalidadeCategoriaSigilo();
        $modalidadeCategoriaSigilo->setValor('TRANSPORTES E TRÂNSITO');
        $modalidadeCategoriaSigilo->setDescricao('TRANSPORTES E TRÂNSITO');

        $manager->persist($modalidadeCategoriaSigilo);

        $this->addReference('ModalidadeCategoriaSigilo-'.$modalidadeCategoriaSigilo->getValor(), $modalidadeCategoriaSigilo);

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
        return 1;
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
