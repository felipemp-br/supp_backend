<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /src/Entity/<?= $entityName ?>.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Entity;

use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class <?= $entityName . "\n" ?>
 *
 * @ORM\Table(
 *      name="<?= $tableName ?>",
 *  )
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="apagadoEm")
 *
 * @package SuppCore\AdministrativoBackend\Entity
 * @author  <?= $author . "\n" ?>
 */
class <?= $entityName ?> implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;

    /**
     * @var int
     *
     * @Groups({
     *     "<?= $entityName ?>",
     *     "<?= $entityName ?>.id",
     * })
     *
     * @ORM\Column(
     *      name="id",
     *      type="integer",
     *      nullable=false
     * )
     * @ORM\Id()
     * @ORM\GeneratedValue("AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Groups({
     *     "<?= $entityName ?>",
     *     "<?= $entityName ?>.uuid",
     * })
     *
     * @ORM\Column(
     *      name="uuid",
     *      type="guid",
     *      nullable=false
     * )
     */
    protected $uuid;

    /**
     * <?= $entityName ?> constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
