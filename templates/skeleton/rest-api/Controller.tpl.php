<?= "<?php\n" ?>
declare(strict_types = 1);
/**
 * /src/Controller/<?= $controllerName ?>.php
 *
 * @author  <?= $author . "\n" ?>
 */
namespace SuppCore\AdministrativoBackend\Controller;

use SuppCore\AdministrativoBackend\Resource\<?= $resourceName ?>;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use OpenApi\Annotations as OA;

/**
 * @Route(path="<?= $routePath ?>")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @OA\Tag(name="<?= $swaggerTag ?>")
 *
 * @package SuppCore\AdministrativoBackend\Controller
 * @author  <?= $author . "\n" ?>
 *
 * @method <?= $resourceName ?> getResource()
 */
class <?= $controllerName ?> extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;

    /** @noinspection PhpMissingParentConstructorInspection */
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * <?= $controllerName ?> constructor.
     *
     * @param <?= $resourceName ?> $resource
     * @param ResponseHandler $responseHandler
     */
    public function __construct(
        <?= $resourceName ?> $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

}
