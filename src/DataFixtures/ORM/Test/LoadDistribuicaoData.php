<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Test;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Distribuicao;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadDistribuicaoData.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class LoadDistribuicaoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $distribuicao = new Distribuicao();

        $distribuicao->setSetorAnterior(null);
        $distribuicao->setLivreBalanceamento(false);
        $distribuicao->setDistribuicaoAutomatica(false);
        $distribuicao->setDataHoraDistribuicao(DateTime::createFromFormat('Y-m-d h:i:s', '2021-12-01 08:00:00'));
        $distribuicao->setAuditoriaDistribuicao(null);
        $distribuicao->setTarefa($this->getReference('Tarefa-TESTE_USER_LEVEL', Tarefa::class));
        $distribuicao->setUsuarioAnterior(null);
        $distribuicao->setUsuarioPosterior($this->getReference('Usuario-00000000007', Usuario::class));
        $distribuicao->setSetorAnterior(null);
        $distribuicao->setSetorPosterior($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $distribuicao->setTipoDistribuicao(1);

        $manager->persist($distribuicao);

        if (!$this->hasReference('Distribuicao-1', Distribuicao::class)) {
            $this->addReference('Distribuicao-1', $distribuicao);
        }

        $distribuicao = new Distribuicao();

        $distribuicao->setLivreBalanceamento(false);
        $distribuicao->setDistribuicaoAutomatica(false);
        $distribuicao->setDataHoraDistribuicao(DateTime::createFromFormat('Y-m-d h:i:s', '2021-12-01 08:00:00'));
        $distribuicao->setAuditoriaDistribuicao('Auditoria Distribuição 2');
        $distribuicao->setTarefa($this->getReference('Tarefa-TESTE_1', Tarefa::class));
        $distribuicao->setUsuarioAnterior(null);
        $distribuicao->setUsuarioPosterior($this->getReference('Usuario-00000000008', Usuario::class));
        $distribuicao->setSetorAnterior(null);
        $distribuicao->setSetorPosterior($this->getReference('Unidade-CONSULTORIA-GERAL DA UNIÃO', Setor::class));
        $distribuicao->setTipoDistribuicao(2);

        $manager->persist($distribuicao);

        if (!$this->hasReference('Distribuicao-2', Distribuicao::class)) {
            $this->addReference('Distribuicao-2', $distribuicao);
        }

        $distribuicao = new Distribuicao();

        $distribuicao->setLivreBalanceamento(false);
        $distribuicao->setDistribuicaoAutomatica(false);
        $distribuicao->setDataHoraDistribuicao(DateTime::createFromFormat('Y-m-d h:i:s', '2021-12-01 08:00:00'));
        $distribuicao->setAuditoriaDistribuicao('Auditoria Distribuição 3');
        $distribuicao->setTarefa($this->getReference('Tarefa-TESTE_SEM_ATIVIDADE', Tarefa::class));
        $distribuicao->setUsuarioAnterior(null);
        $distribuicao->setUsuarioPosterior($this->getReference('Usuario-00000000010', Usuario::class));
        $distribuicao->setSetorAnterior(null);
        $distribuicao->setSetorPosterior($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $distribuicao->setTipoDistribuicao(3);

        $manager->persist($distribuicao);

        if (!$this->hasReference('Distribuicao-3', Distribuicao::class)) {
            $this->addReference('Distribuicao-3', $distribuicao);
        }

        $distribuicao = new Distribuicao();

        $distribuicao->setLivreBalanceamento(false);
        $distribuicao->setDistribuicaoAutomatica(false);
        $distribuicao->setDataHoraDistribuicao(DateTime::createFromFormat('Y-m-d h:i:s', '2021-12-01 08:00:00'));
        $distribuicao->setAuditoriaDistribuicao('Auditoria Distribuição 4');
        $distribuicao->setTarefa($this->getReference('Tarefa-TESTE_SEM_ATIVIDADE', Tarefa::class));
        $distribuicao->setUsuarioAnterior(null);
        $distribuicao->setUsuarioPosterior($this->getReference('Usuario-00000000010', Usuario::class));
        $distribuicao->setSetorAnterior(null);
        $distribuicao->setSetorPosterior($this->getReference('Unidade-ADVOCACIA-GERAL DA UNIÃO', Setor::class));
        $distribuicao->setTipoDistribuicao(4);

        $manager->persist($distribuicao);

        if (!$this->hasReference('Distribuicao-4', Distribuicao::class)) {
            $this->addReference('Distribuicao-4', $distribuicao);
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
        return 6;
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
