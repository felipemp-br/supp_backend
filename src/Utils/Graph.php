<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Utils;

use RuntimeException;

/**
 * Class Graph
 */
class Graph
{
    private array $adj = [];

    /**
     * @param string $source
     *
     * @return self
     */
    public function addVertice(string $source): self
    {
        if (!isset($this->adj[$source])) {
            $this->adj[$source] = [];
        }

        return $this;
    }

    /**
     * @param string $source
     * @param string $dest
     *
     * @return self
     */
    public function addEdge(string $source, string $dest): self
    {
        $this->addVertice($source);
        $this->addVertice($dest);
        $this->adj[$source][] = $dest;

        return $this;
    }


    /**
     * this function is a variation of DFSUtil() in https://www.geeksforgeeks.org/archives/18212
     *
     * @param string      $node
     * @param array       $visited
     * @param array       $recStack
     * @param string|null $ciclicElement
     *
     * @return bool
     */
    private function isCyclicUtil(string $node, array &$visited, array &$recStack, ?string &$ciclicElement): bool
    {
        // Mark the current node as visited and part of recursion stack
        if ($recStack[$node]) {
            $ciclicElement = $node;
            return true;
        }

        if ($visited[$node]) {
            return false;
        }

        $visited[$node]  = true;
        $recStack[$node] = true;

        foreach ($this->adj[$node] as $child) {
            if ($this->isCyclicUtil($child, $visited, $recStack, $ciclicElement)) {
                return true;
            }
        }

        $recStack[$node] = false;

        return false;
    }

    /**
     * Returns true if the graph contains a cycle, else false.
     * this function is a variation of DFS() in https://www.geeksforgeeks.org/archives/18212
     *
     * @param string|null $ciclicElement
     *
     * @return bool
     */
    public function isCyclic(string &$ciclicElement = null): bool
    {
        // mark all the vertices as not visited and not part of recursion stack
        $visited  = [];
        $recStack = [];
        foreach (array_keys($this->adj) as $node) {
            $visited[$node] = $recStack[$node] = false;
        }


        // call the recursive helper function to detect cycle in different DFS trees
        foreach (array_keys($this->adj) as $node) {
            if ($this->isCyclicUtil($node, $visited, $recStack, $ciclicElement)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return sorted topologic.
     *
     * @param bool $orderByLevel
     *
     * @return array
     */
    public function topologicalSort(bool $orderByLevel = true): array
    {
        // create a array to store indegrees of all vertices
        // initialize all indegrees as 0
        $indegree = [];
        foreach (array_keys($this->adj) as $node) {
            $indegree[$node] = 0;
        }

        // Traverse adjacency lists to fill indegrees of
        // vertices. This step takes O(V+E) time
        foreach ($this->adj as $node => $neighbours) {
            foreach ($neighbours as $neighbour) {
                $indegree[$neighbour]++;
            }
        }

        // Create a queue and enqueue all vertices with indegree 0
        $queue = array_filter(
            $indegree,
            fn ($v) => $v === 0
        );

        // initialize count of visited vertices
        $cnt = 0;

        // create a vector to store result: topological ordering of the vertices
        $order = [];

        // One by one dequeue vertices from queue and enqueue adjacents if indegree of adjacent becomes 0
        while (count($queue)) {
            // extract front of queue (or perform dequeue)
            // and add it to topological order
            $node  = array_key_first($queue);
            $level = array_shift($queue);

            if ($orderByLevel) {
                $order[$level][] = $node;
            } else {
                $order[] = $node;
            }

            // iterate through all its neighbouring nodes of dequeued node and
            // decrease their in-degreeby 1
            foreach ($this->adj[$node] as $neighbour) {
                // if in-degree becomes zero, add it to queue
                if (--$indegree[$neighbour] === 0) {
                    $queue[$neighbour] = $level + 1;
                }
            }

            $cnt++;
        }

        // check if there was a cycle
        if ($cnt !== count($this->adj)) {
            throw new RuntimeException('there exists a cycle in the graph');
        }

        if ($orderByLevel) {
            ksort($order);
        }

        return $order;
    }
}
