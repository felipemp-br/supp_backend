<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadModalidadeQualificacaoPessoaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeQualificacaoPessoa;

/**
 * Class LoadModalidadeQualificacaoPessoaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeQualificacaoPessoaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeQualificacaoPessoa = new ModalidadeQualificacaoPessoa();
        $modalidadeQualificacaoPessoa->setValor('PESSOA FÍSICA');
        $modalidadeQualificacaoPessoa->setDescricao('PESSOA FÍSICA');

        $manager->persist($modalidadeQualificacaoPessoa);

        $this->addReference(
            'ModalidadeQualificacaoPessoa-'.$modalidadeQualificacaoPessoa->getValor(),
            $modalidadeQualificacaoPessoa
        );

        $modalidadeQualificacaoPessoa = new ModalidadeQualificacaoPessoa();
        $modalidadeQualificacaoPessoa->setValor('PESSOA JURÍDICA');
        $modalidadeQualificacaoPessoa->setDescricao('PESSOA JURÍDICA');

        $manager->persist($modalidadeQualificacaoPessoa);

        $this->addReference(
            'ModalidadeQualificacaoPessoa-'.$modalidadeQualificacaoPessoa->getValor(),
            $modalidadeQualificacaoPessoa
        );

        $modalidadeQualificacaoPessoa = new ModalidadeQualificacaoPessoa();
        $modalidadeQualificacaoPessoa->setValor('AUTORIDADE');
        $modalidadeQualificacaoPessoa->setDescricao('AUTORIDADE');

        $manager->persist($modalidadeQualificacaoPessoa);

        $this->addReference(
            'ModalidadeQualificacaoPessoa-'.$modalidadeQualificacaoPessoa->getValor(),
            $modalidadeQualificacaoPessoa
        );

        $modalidadeQualificacaoPessoa = new ModalidadeQualificacaoPessoa();
        $modalidadeQualificacaoPessoa->setValor('ÓRGÃO REPRESENTAÇÃO');
        $modalidadeQualificacaoPessoa->setDescricao('ÓRGÃO REPRESENTAÇÃO');

        $manager->persist($modalidadeQualificacaoPessoa);

        $this->addReference(
            'ModalidadeQualificacaoPessoa-'.$modalidadeQualificacaoPessoa->getValor(),
            $modalidadeQualificacaoPessoa
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
