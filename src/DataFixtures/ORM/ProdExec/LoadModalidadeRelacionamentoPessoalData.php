<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadModalidadeRelacionamentoPessoalData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRelacionamentoPessoal;

/**
 * Class LoadModalidadeRelacionamentoPessoalData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadModalidadeRelacionamentoPessoalData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $modalidadeRelacionamentoPessoal = new ModalidadeRelacionamentoPessoal();
        $modalidadeRelacionamentoPessoal->setValor('REPRESENTAÇÃO LEGAL DE ASCENDENTE (PAIS)');
        $modalidadeRelacionamentoPessoal->setDescricao('REPRESENTAÇÃO LEGAL DE ASCENDENTE (PAIS)');

        $manager->persist($modalidadeRelacionamentoPessoal);

        $this->addReference('ModalidadeRelacionamentoPessoal-'.$modalidadeRelacionamentoPessoal->getValor(), $modalidadeRelacionamentoPessoal);

        $modalidadeRelacionamentoPessoal = new ModalidadeRelacionamentoPessoal();
        $modalidadeRelacionamentoPessoal->setValor('ASSISTÊNCIA DOS PAIS');
        $modalidadeRelacionamentoPessoal->setDescricao('ASSISTÊNCIA DOS PAIS');

        $manager->persist($modalidadeRelacionamentoPessoal);

        $this->addReference('ModalidadeRelacionamentoPessoal-'.$modalidadeRelacionamentoPessoal->getValor(), $modalidadeRelacionamentoPessoal);

        $modalidadeRelacionamentoPessoal = new ModalidadeRelacionamentoPessoal();
        $modalidadeRelacionamentoPessoal->setValor('SUBSTITUIÇÃO OU REPRESENTAÇÃO PROCESSUAL NOS CASOS DE AÇÕES COLETIVAS');
        $modalidadeRelacionamentoPessoal->setDescricao('SUBSTITUIÇÃO OU REPRESENTAÇÃO PROCESSUAL NOS CASOS DE AÇÕES COLETIVAS');

        $manager->persist($modalidadeRelacionamentoPessoal);

        $this->addReference('ModalidadeRelacionamentoPessoal-'.$modalidadeRelacionamentoPessoal->getValor(), $modalidadeRelacionamentoPessoal);

        $modalidadeRelacionamentoPessoal = new ModalidadeRelacionamentoPessoal();
        $modalidadeRelacionamentoPessoal->setValor('TUTORIA');
        $modalidadeRelacionamentoPessoal->setDescricao('TUTORIA');

        $manager->persist($modalidadeRelacionamentoPessoal);

        $this->addReference('ModalidadeRelacionamentoPessoal-'.$modalidadeRelacionamentoPessoal->getValor(), $modalidadeRelacionamentoPessoal);

        $modalidadeRelacionamentoPessoal = new ModalidadeRelacionamentoPessoal();
        $modalidadeRelacionamentoPessoal->setValor('CURADORIA');
        $modalidadeRelacionamentoPessoal->setDescricao('CURADORIA');

        $manager->persist($modalidadeRelacionamentoPessoal);

        $this->addReference('ModalidadeRelacionamentoPessoal-'.$modalidadeRelacionamentoPessoal->getValor(), $modalidadeRelacionamentoPessoal);

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
        return ['prodexec'];
    }
}
