<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Security.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use InvalidArgumentException;
use OpenApi\Annotations as OA;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function array_filter;
use function array_values;
use function count;

/**
 * Class Security.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Security
{
    private readonly Responses $responses;

    public function __construct(Responses $responses)
    {
        $this->responses = $responses;
    }

    /**
     * Method to process rest action '#[Security] attribute.
     * If this annotation is present we need to following things:
     *  1) Add 'Authorization' header parameter
     *  2) Add 401 response
     *  2) Add 403 response.
     *
     * @throws InvalidArgumentException
     */
    public function process(OA\Operation $operation, RouteModel $routeModel): void
    {
        $filter = fn ($metadata): bool => $metadata instanceof IsGranted;

        if (1 === count(array_values(array_filter($routeModel->getMethodMetadata(), $filter)))) {
            // Add Authorization
            $operation->security = [
                [
                    'Bearer' => [],
                ],
            ];

            // Attach 401 and 403 responses
            $this->responses->add401($operation);
            $this->responses->add403($operation);
        }
    }
}
