<?php

declare(strict_types=1);
/**
 * /src/DataFixtures/ORM/ProdExec/LoadCampoData.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataFixtures\ORM\ProdExec;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SuppCore\AdministrativoBackend\Entity\Campo;
use SuppCore\AdministrativoBackend\Fields\FieldsManager;

/**
 * Class LoadCampoData.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LoadCampoData extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(
        private readonly ?FieldsManager $fieldsManager = null
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fieldsManager->getFields() as $field) {
            $fieldConfig = $field->getConfig();
            // Create new entity
            $entity = new Campo();
            $entity->setNome($fieldConfig['info']['nome']);
            $entity->setDescricao($fieldConfig['info']['descricao']);
            $entity->setHtml($fieldConfig['info']['html']);
            $entity->setAtivo(true);

            // Persist entity
            $manager->persist($entity);

            $this->addReference('Campo-'.$fieldConfig['info']['nome'], $entity);
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
