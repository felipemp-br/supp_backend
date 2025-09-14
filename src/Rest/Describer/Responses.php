<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Responses.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use OpenApi\Annotations as OA;
use OpenApi\Generator as OAGenerator;

use function sprintf;

/**
 * Class Responses.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Responses
{
    public function add401(OA\Operation $operation): void
    {
        $data = [
            'description' => 'Invalid token',
            'response' => 401,
            'content' => [
                new OA\MediaType(
                    [
                        'mediaType' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'code' => [
                                    'type' => 'integer',
                                    'description' => 'Error code',
                                ],
                                'message' => [
                                    'type' => 'string',
                                    'description' => 'Error description',
                                ],
                            ],
                            'examples' => [
                                'Token not found' => '{code: 401, message: JWT Token not found}',
                                'Expired token' => '{code: 401, message: Expired JWT Token}',
                            ],
                        ],
                    ]
                ),
            ],
            '_context' => $operation->_context,
        ];

        $response = new OA\Response($data);

        if (OAGenerator::UNDEFINED === $operation->responses) {
            $operation->responses = [];
        }

        $operation->responses[] = $response;
    }

    public function add403(OA\Operation $operation): void
    {
        $data = [
            'description' => 'Access denied',
            'response' => 403,
            'content' => [
                new OA\MediaType(
                    [
                        'mediaType' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'code' => [
                                    'type' => 'integer',
                                    'description' => 'Error code',
                                ],
                                'message' => [
                                    'type' => 'string',
                                    'description' => 'Error description',
                                ],
                            ],
                            'examples' => [
                                'Access denied' => '{message: Access denied., code: 0, status: 403}',
                            ],
                        ],
                    ]
                ),
            ],
            '_context' => $operation->_context,
        ];

        $response = new OA\Response($data);

        if (OAGenerator::UNDEFINED === $operation->responses) {
            $operation->responses = [];
        }

        $operation->responses[] = $response;
    }

    public function add404(OA\Operation $operation): void
    {
        $data = [
            'description' => 'Not found',
            'response' => 404,
            'content' => [
                new OA\MediaType(
                    [
                        'mediaType' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'code' => [
                                    'type' => 'integer',
                                    'description' => 'Error code',
                                ],
                                'message' => [
                                    'type' => 'string',
                                    'description' => 'Error description',
                                ],
                            ],
                            'examples' => [
                                'Access denied' => '{message: Not found, code: 0, status: 404}',
                            ],
                        ],
                    ]
                ),
            ],
            '_context' => $operation->_context,
        ];

        $response = new OA\Response($data);

        if (OAGenerator::UNDEFINED === $operation->responses) {
            $operation->responses = [];
        }

        $operation->responses[] = $response;
    }

    public function addOk(OA\Operation $operation, string $description, int $statusCode, string $entityName): void
    {
        $data = [
            'description' => sprintf($description, $entityName),
            'response' => $statusCode,
            '_context' => $operation->_context,
        ];

        $response = new OA\Response($data);

        if (OAGenerator::UNDEFINED === $operation->responses) {
            $operation->responses = [];
        }

        $operation->responses[] = $response;
    }
}
