<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Prod/LoadTipoSolicitacaoAutomatizadaData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Prod;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;

/**
 * Class LoadTipoSolicitacaoAutomatizadaData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoSolicitacaoAutomatizadaData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $formularioPacificaSalarioMaternidadeRural = $manager
            ->createQuery(
                "
                SELECT f 
                FROM SuppCore\AdministrativoBackend\Entity\Formulario f 
                WHERE f.nome = 'FORMULÁRIO DE REQUERIMENTO PACIFICA SALÁRIO MATERNIDADE RURAL'"
            )
            ->getOneOrNullResult() ?:
                $this->getReference('Formulario-FORMULÁRIO DE REQUERIMENTO PACIFICA SALÁRIO MATERNIDADE RURAL',
                    Formulario::class
                );

        if (null === $manager
                ->createQuery(
                    "
                SELECT tsa 
                FROM SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada tsa 
                WHERE tsa.sigla = 'PACIFICA_SAL_MAT_RURAL'"
                )
                ->getOneOrNullResult()) {
            $tipoSolicitacaoAutomatizada = new TipoSolicitacaoAutomatizada();
            $tipoSolicitacaoAutomatizada->setSigla('PACIFICA_SAL_MAT_RURAL');
            $tipoSolicitacaoAutomatizada->setDescricao('PACIFICA - SALARIO MATERNIDADE RURAL');
            $tipoSolicitacaoAutomatizada->setFormulario($formularioPacificaSalarioMaternidadeRural);

            $manager->persist($tipoSolicitacaoAutomatizada);

            $this->addReference(
                'TipoSolicitacaoAutomatizada-'.$tipoSolicitacaoAutomatizada->getSigla(),
                $tipoSolicitacaoAutomatizada
            );
        }

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
        return ['prod', 'dev', 'test'];
    }
}
