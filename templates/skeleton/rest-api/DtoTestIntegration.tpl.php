<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /tests/Integration/DTO/<?= $entityName ?>Test.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Tests\Integration\DTO;

use SuppCore\AdministrativoBackend\DTO\<?= $entityName ?> as <?= $entityName ?>Dto;
use SuppCore\AdministrativoBackend\Entity\<?= $entityName ?> as <?= $entityName ?>Entity;

/**
 * Class <?= $entityName ?>Test
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\DTO
 * @author  <?= $author . "\n" ?>
 */
class <?= $entityName ?>Test extends DtoTestCase
{
    protected $dtoClass = <?= $entityName ?>Dto::class;

    public function testThatLoadMethodWorks(): void
    {
        // Create entity
        $entity = new <?= $entityName ?>Entity();

        // Create DTO and load entity
        $dto = new <?= $entityName ?>Dto();
        $dto->load($entity);

        static::assertSame($entity->getId(), $dto->getId());
    }
}
