<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/FindOneMethod.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use LogicException;
use ReflectionClass;
use SuppCore\AdministrativoBackend\Form\Attributes\Cacheable as CacheableAttribute;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Trait FindOneMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait FindOneMethod
{
    // Traits
    use AbstractGenericMethods;

    /**
     * Generic 'findOneMethod' method for REST resources.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    public function findOneMethod(Request $request, int $id, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $populate = RequestHandler::getPopulate($request, $this->getResource());
        $context = RequestHandler::getContext($request);
        // sorting faz sentido para o populate
        $orderBy = RequestHandler::getOrderBy($request);

        try {
            // Fetch data from database
            $dtoClass = $this->getResource()->getDtoClass();

            $reflectionClassDTO = new ReflectionClass($dtoClass);

            foreach ($reflectionClassDTO->getAttributes() as $attribute) {
                if (CacheableAttribute::class === $attribute->getName()) {
                    $cacheableMetadata = $attribute->newInstance();
                    break;
                }
            }

            if ($cacheableMetadata ?? null) {
                $redisClient = $this->getResource()->getRedisClient();
                if ($redisClient->hGet($dtoClass, $request->getRequestUri())) {
                    $response = unserialize($redisClient->hGet($dtoClass, $request->getRequestUri()));
                } else {
                    $response = $this->getResponseHandler()->createResponse(
                        $request,
                        $this->getResource()->findOne($id, $populate, $context, $orderBy)
                    );
                    $redisClient->hSet(
                        $dtoClass,
                        $request->getRequestUri(),
                        serialize($response)
                    );
                    $redisClient->expire($dtoClass, 86400);
                }
            } else {
                $transactionId = $this->transactionManager->begin();

                foreach ($context as $name => $value) {
                    $this->transactionManager->addContext(
                        new Context($name, $value),
                        $transactionId
                    );
                }

                $data = $this->getResource()->findOne($id, $populate, $context, $orderBy);

                $this->transactionManager->commit($transactionId);

                $response = $this
                    ->getResponseHandler()
                    ->createResponse($request, $data);
            }

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
