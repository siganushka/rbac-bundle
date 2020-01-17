<?php

namespace Siganushka\RBACBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait RoleableTrait
{
    /**
     * @ORM\Column(type="json")
     */
    private $nodes = [];

    /**
     * Check node is exists.
     *
     * @return self
     */
    public function hasNode(string $node): bool
    {
        return \in_array($node, $this->nodes);
    }

    /**
     * Add node.
     *
     * @return self
     */
    public function addNode(string $node): RoleableInterface
    {
        if (!$this->hasNode($node)) {
            $this->nodes[] = $node;
        }

        return $this;
    }

    /**
     * Remove node.
     *
     * @return self
     */
    public function removeNode(string $node): RoleableInterface
    {
        if (false !== $key = array_search($node, $this->nodes, true)) {
            unset($this->nodes[$key]);
            // reset index of nodes
            $this->nodes = array_values($this->nodes);
        }

        return $this;
    }

    /**
     * Get nodes.
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * Set nodes.
     *
     * @return self
     */
    public function setNodes(array $nodes): RoleableInterface
    {
        $this->nodes = array_values(array_unique($nodes));

        return $this;
    }

    /**
     * Clear nodes.
     *
     * @return self
     */
    public function clearNodes(): RoleableInterface
    {
        $this->nodes = [];

        return $this;
    }
}
