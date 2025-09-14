<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\Dev;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Usuario;

/**
 * Class LoadAssuntoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadAssuntoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $processos = [
            $this->getReference('Processo-00100000001202321', Processo::class),
            $this->getReference('Processo-00100000002202375', Processo::class),
            $this->getReference('Processo-00100000003202310', Processo::class),
            $this->getReference('Processo-00100000003202364', Processo::class),
            $this->getReference('Processo-00100000002202375', Processo::class),
            $this->getReference('Processo-00100000003202310', Processo::class),
            $this->getReference('Processo-00100000003202364', Processo::class),
        ];

        foreach ($processos as $processo) {
            $assunto = (new Assunto())
                ->setPrincipal(true)
                ->setAssuntoAdministrativo(
                    $this->getReference('AssuntoAdministrativo-ATIVIDADE MEIO', AssuntoAdministrativo::class)
                )
                ->setProcesso($processo)
                ->setApagadoEm(null)
                ->setAtualizadoEm(date_create('now'))
                ->setCriadoEm(date_create('now'))
                ->setCriadoPor($this->getReference('Usuario-00000000004', Usuario::class));

            // Persist entity
            $manager->persist($assunto);
        }

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
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
        return ['dev'];
    }
}
