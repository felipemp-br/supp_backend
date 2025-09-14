<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeGeneroPessoaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeGeneroPessoa;

/**
 * Class LoadModalidadeGeneroPessoaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeGeneroPessoaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeGeneroPessoa = new ModalidadeGeneroPessoa();
        $modalidadeGeneroPessoa->setValor('MASCULINO');
        $modalidadeGeneroPessoa->setDescricao('MASCULINO');

        $manager->persist($modalidadeGeneroPessoa);

        $this->addReference('ModalidadeGeneroPessoa-'.$modalidadeGeneroPessoa->getValor(), $modalidadeGeneroPessoa);

        $modalidadeGeneroPessoa = new ModalidadeGeneroPessoa();
        $modalidadeGeneroPessoa->setValor('FEMININO');
        $modalidadeGeneroPessoa->setDescricao('FEMININO');

        $manager->persist($modalidadeGeneroPessoa);

        $this->addReference('ModalidadeGeneroPessoa-'.$modalidadeGeneroPessoa->getValor(), $modalidadeGeneroPessoa);

        $modalidadeGeneroPessoa = new ModalidadeGeneroPessoa();
        $modalidadeGeneroPessoa->setValor('DESCONHECIDO');
        $modalidadeGeneroPessoa->setDescricao('DESCONHECIDO');

        $manager->persist($modalidadeGeneroPessoa);

        $this->addReference('ModalidadeGeneroPessoa-'.$modalidadeGeneroPessoa->getValor(), $modalidadeGeneroPessoa);

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
