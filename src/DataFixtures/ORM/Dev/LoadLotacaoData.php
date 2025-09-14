<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Dev/LoadLotacaoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Setor;

/**
 * Class LoadLotacaoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadLotacaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-00000000002', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-PROTOCOLO-AGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-00000000002-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-00000000003', Colaborador::class));
        $lotacao->setArquivista(true);
        $lotacao->setSetor($this->getReference('Setor-ARQUIVO-AGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-00000000003-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-00000000004', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-00000000004-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-00000000005', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-AGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-00000000005-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-10000000002', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-PROTOCOLO-PGF-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-10000000002-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-10000000003', Colaborador::class));
        $lotacao->setArquivista(true);
        $lotacao->setSetor($this->getReference('Setor-ARQUIVO-PGF-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-10000000003-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-10000000004', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-PGF-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-10000000004-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-10000000005', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-PGF-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-10000000005-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-20000000002', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-PROTOCOLO-PGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-20000000002-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-20000000003', Colaborador::class));
        $lotacao->setArquivista(true);
        $lotacao->setSetor($this->getReference('Setor-ARQUIVO-PGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-20000000003-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-20000000004', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-PGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-20000000004-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-20000000005', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-PGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-20000000005-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-30000000002', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-PROTOCOLO-CGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-30000000002-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-30000000003', Colaborador::class));
        $lotacao->setArquivista(true);
        $lotacao->setSetor($this->getReference('Setor-ARQUIVO-CGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-30000000003-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-30000000004', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-CGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-30000000004-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-30000000005', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-CGU-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-30000000005-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-40000000002', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-PROTOCOLO-SGA-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-40000000002-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-40000000003', Colaborador::class));
        $lotacao->setArquivista(true);
        $lotacao->setSetor($this->getReference('Setor-ARQUIVO-SGA-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-40000000003-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-40000000004', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-SGA-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-40000000004-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
        );

        $lotacao = new Lotacao();
        $lotacao->setColaborador($this->getReference('Colaborador-40000000005', Colaborador::class));
        $lotacao->setSetor($this->getReference('Setor-SECRETARIA-SGA-SEDE', Setor::class));
        $lotacao->setPrincipal(true);

        $manager->persist($lotacao);

        $this->addReference(
            'Lotacao-Colaborador-40000000005-'.$lotacao->getSetor()->getNome(
            ).'-'.$lotacao->getSetor()->getUnidade()->getSigla(),
            $lotacao
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
        return 5;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to.
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['dev'];
    }
}
