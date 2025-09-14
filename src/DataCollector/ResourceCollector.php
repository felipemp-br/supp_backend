<?php

declare(strict_types=1);
/**
 * /src/DataCollector/ResourceCollector.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DataCollector;

use Psr\Log\LoggerInterface as Logger;
use SuppCore\AdministrativoBackend\Monolog\Handler\CollectionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class ResourceCollector.
 */
class ResourceCollector extends DataCollector
{
    private const UNDEFINED_ROUTE = 'undefined_route';

    private Logger $logger;

    /**
     * @param Logger $resourceLogger
     */
    public function __construct(
        Logger $resourceLogger
    ) {
        $this->logger = $resourceLogger;
    }

    /**
     * @param Request         $request
     * @param Response        $response
     * @param \Throwable|null $exception
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $this->data['events'] = [];

        foreach ($this->logger->getHandlers() as $handler) {
            if ($handler instanceof CollectionHandler) {
                $this->handleRecords($this->getRoute($request), $handler->getRecords());
                $handler->clearRecords();
            }
        }
    }

    /**
     * @param $route
     * @param $records
     */
    private function handleRecords($route, $records)
    {
        foreach ($records as $record) {
            // First record will never have context.
            if (!empty($record['context'])) {
                $record['context']['datetime'] = $record['datetime'];
                $this->data['events'][] = $record['context'];
            }
        }
        if (count($this->data['events']) > 0) {
            $this->data['tree'] = $this->buildTree($this->data['events']);
        }
    }

    public function reset()
    {
        $this->data = [];
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->data['events'];
    }

    /**
     * @return mixed
     */
    public function getTree()
    {
        return $this->data['tree'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'supp_core.administrativo_backend.resource_collector';
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    private function getRoute(Request $request)
    {
        $route = $request->attributes->get('_route');

        return empty($route) ? self::UNDEFINED_ROUTE : $route;
    }

    /**
     * @param array $flatList
     *
     * @return mixed
     */
    private function buildTree(array $flatList)
    {
        $grouped = [];
        foreach ($flatList as $node) {
            $grouped[$node['parent']][] = $node;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling['id'];
                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }

            return $siblings;
        };

        return $fnBuilder(reset($grouped));
    }
}
