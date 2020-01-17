<?php

namespace Siganushka\RBACBundle\Model;

interface RoleableInterface
{
    /**
     * Check node is exists.
     */
    public function hasNode(string $node): bool;

    /**
     * Add node.
     */
    public function addNode(string $node): self;

    /**
     * Remove node.
     */
    public function removeNode(string $node): self;

    /**
     * Get nodes.
     */
    public function getNodes(): array;

    /**
     * Set nodes.
     */
    public function setNodes(array $nodes): self;

    /**
     * Clear nodes.
     */
    public function clearNodes(): self;
}
