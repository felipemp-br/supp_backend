<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Tags.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use OpenApi\Annotations as OA;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;

use function array_filter;
use function array_values;
use function count;

/**
 * Class Tags.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Tags
{
    /**
     * Method to process operation 'tags'.
     */
    public function process(OA\Operation $operation, RouteModel $routeModel): void
    {
        // Initialize main data array
        $data = [
            'tags' => [],
        ];

        $this->processTags($routeModel, $data);

        // Merge data to operation
        $operation->tags = $data['tags'];
    }

    private function processTags(RouteModel $routeModel, array &$data): void
    {
        $filter = fn ($metadata): bool => $metadata instanceof OA\Tag;

        $tags = array_values(array_filter($routeModel->getControllerMetadata(), $filter));

        // If controller has 'OA\Tag' attribute or annotation we will use that as a tag
        if (1 === count($tags)) {
            $data['tags'][] = $tags[0]->name;
        }
    }
}
