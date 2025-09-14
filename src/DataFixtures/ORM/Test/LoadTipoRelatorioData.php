<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/Test/LoadTipoRelatorioData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\EspecieRelatorio;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;

/**
 * Class LoadTipoRelatorioData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadTipoRelatorioData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $tiporelatorio = new TipoRelatorio();
        $tiporelatorio->setNome('GERENCIAL');
        $tiporelatorio->setTemplateHTML('TemplateHTML1');
        $tiporelatorio->setDQL('SQLTESTE1');
        $tiporelatorio->setParametros('parametros');
        $tiporelatorio->setDescricao('Descrição 1');
        $tiporelatorio->setEspecieRelatorio($this->getReference('Especie-GERENCIAL', EspecieRelatorio::class));
        $tiporelatorio->setAtivo(true);
        $tiporelatorio->setLimite(1);
        $manager->persist($tiporelatorio);
        $this->addReference('TipoRelatorio-'.$tiporelatorio->getNome(), $tiporelatorio);

        $tiporelatorio = new TipoRelatorio();
        $tiporelatorio->setNome('TESTE');
        $tiporelatorio->setTemplateHTML('TemplateHTML2');
        $tiporelatorio->setDQL('SQLTESTE1');
        $tiporelatorio->setParametros('parametros');
        $tiporelatorio->setDescricao('Descrição 2');
        $tiporelatorio->setEspecieRelatorio($this->getReference('Especie-ATIVIDADE', EspecieRelatorio::class));
        $tiporelatorio->setAtivo(true);
        $tiporelatorio->setLimite(1);
        $manager->persist($tiporelatorio);
        $this->addReference('TipoRelatorio-'.$tiporelatorio->getNome(), $tiporelatorio);

        $tiporelatorio = new TipoRelatorio();
        $tiporelatorio->setNome('RELATÓRIO');
        $tiporelatorio->setTemplateHTML('TemplateHTML3');
        $tiporelatorio->setDQL('SQLTESTE1');
        $tiporelatorio->setParametros('parametros');
        $tiporelatorio->setDescricao('Descrição 3');
        $tiporelatorio->setEspecieRelatorio($this->getReference('Especie-TABELAS', EspecieRelatorio::class));
        $tiporelatorio->setAtivo(true);
        $tiporelatorio->setLimite(1);
        $manager->persist($tiporelatorio);
        $this->addReference('TipoRelatorio-'.$tiporelatorio->getNome(), $tiporelatorio);

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['test'];
    }
}
