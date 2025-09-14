<?= "<?php\n" ?>
declare(strict_types = 1);
/**
* /src/DTO/<?= $entityName ?>.php
*
* @author  <?= $author . "\n" ?>
*/
namespace SuppCore\AdministrativoBackend\DTO;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?> as <?= $entityName ?>Entity;
use Symfony\Component\Validator\Constraints as Assert;
use SuppCore\AdministrativoBackend\Form\Annotations as Form;

/**
 * Class <?= $entityName . "\n" ?>
 *
 * @package SuppCore\AdministrativoBackend\DTO
 * @author  <?= $author . "\n" ?>
 *
 * @Form\Form()
 */
class <?= $entityName ?> extends RestDto
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Method to load DTO data from specified entity.
     *
     * @param EntityInterface|<?= $entityName ?>Entity $entity
     *
     * @return RestDtoInterface|<?= $entityName . "\n" ?>
     */
    public function load(EntityInterface $entity): RestDtoInterface
    {
        $this->id = $entity->getId();

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return <?= $entityName . "\n" ?>
     */
    public function setId(?int $id = null): <?= $entityName . "\n" ?>
    {
        $this->setVisited('id');

        $this->id = $id;

        return $this;
    }
}
