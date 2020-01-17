<?php

namespace Siganushka\RBACBundle\Node;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class NodeCollection implements \IteratorAggregate, \Countable
{
    private $nodes = [];

    public function __construct(RouterInterface $router, array $config = [])
    {
        foreach ($router->getRouteCollection() as $name => $route) {
            if (!$this->matchRoute($route, $config['firewall_pattern'])
                || $this->matchRoute($route, $config['ignore_pattern'])) {
                continue;
            }

            $options = [
                'path' => $route->getPath(),
                'host' => $route->getHost(),
                'methods' => $route->getMethods(),
                'schemes' => $route->getSchemes(),
            ];

            if (\in_array($name, $config['fully_routes'], true)) {
                $options['is_authenticated_fully'] =
                $options['checked'] = true;
            }

            if (\in_array($name, $config['remembered_routes'], true)) {
                $options['is_authenticated_remembered'] = true;
            }

            if (\in_array($name, $config['anonymously_routes'], true)) {
                $options['is_authenticated_anonymously'] =
                $options['checked'] =
                $options['disabled'] = true;
            }

            $this->nodes[$name] = new Node($options);
        }
    }

    public function matchRoute(Route $route, ?string $pattern)
    {
        if (null !== $pattern && !preg_match('{'.$pattern.'}', rawurldecode($route->getPath()))) {
            return false;
        }

        return true;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    public function count()
    {
        return \count($this->nodes);
    }

    public function all()
    {
        return $this->nodes;
    }

    public function get($name)
    {
        return isset($this->nodes[$name]) ? $this->nodes[$name] : null;
    }

    public function has($name)
    {
        return isset($this->nodes[$name]);
    }

    public function remove($name)
    {
        foreach ((array) $name as $n) {
            unset($this->nodes[$n]);
        }
    }

    public function keys()
    {
        return array_keys($this->nodes);
    }

    public function values()
    {
        return array_values($this->nodes);
    }
}
