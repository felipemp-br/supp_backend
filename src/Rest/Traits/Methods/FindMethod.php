<?php

declare(strict_types=1);
/**
 * /src/Rest/Traits/Methods/FindMethod.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Traits\Methods;

use LogicException;
use ReflectionClass;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Form\Attributes\Cacheable as CacheableAttribute;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Trait FindMethod.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait FindMethod
{
    // Traits
    use AbstractGenericMethods;

    /**
     * Generic 'findMethod' method for REST resources.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    public function findMethod(Request $request, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $search = RequestHandler::getSearchTerms($request);
        $populate = RequestHandler::getPopulate($request, $this->getResource());
        $context = RequestHandler::getContext($request);

        try {
            // Fetch data from database
            $criteria = RequestHandler::getCriteria($request);
            $this->processCriteria($criteria);

            $entityClass = $this->getResource()->getRepository()->getEntityName();

            $reflectionClass = new ReflectionClass($entityClass);

            if ((!isset($context['isAdmin'])) || (true !== $context['isAdmin'])) {
                foreach ($reflectionClass->getAttributes() as $attribute) {
                    if (Enableable::class === $attribute->getName()) {
                        $classAttribute = $attribute->newInstance();
                        break;
                    }
                }

                if ($classAttribute ?? null) {
                    $criteria['ativo'] = 'eq:true';
                }

                if ('SuppCore\AdministrativoBackend\Entity\Usuario' === $entityClass) {
                    $criteria['enabled'] = 'eq:true';
                }
            }

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
                        $this->getResource()->find($criteria, $orderBy, $limit, $offset, $search, $populate)
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

                $data = $this->getResource()->find($criteria, $orderBy, $limit, $offset, $search, $populate);

                $this->transactionManager->commit($transactionId);

                $response = $this
                    ->getResponseHandler()
                    ->createResponse(
                        $request,
                        $data
                    );
            }

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
