<?php

namespace Siganushka\RBACBundle\Node;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Node implements \ArrayAccess
{
    protected $options = [];

    public function __construct(array $options = [])
    {
        $defaults = [
            'path' => null,
            'host' => null,
            'methods' => [],
            'schemes' => [],
            'checked' => false,
            'disabled' => false,
            'is_authenticated_fully' => false,
            'is_authenticated_remembered' => false,
            'is_authenticated_anonymously' => false,
        ];

        $resolver = new OptionsResolver();
        $resolver->setDefaults($defaults);
        $resolver->setAllowedTypes('path', 'string');
        $resolver->setAllowedTypes('host', ['null', 'string']);
        $resolver->setAllowedTypes('methods', 'array');
        $resolver->setAllowedTypes('schemes', 'array');
        $resolver->setAllowedTypes('checked', 'boolean');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $resolver->setAllowedTypes('is_authenticated_fully', 'boolean');
        $resolver->setAllowedTypes('is_authenticated_remembered', 'boolean');
        $resolver->setAllowedTypes('is_authenticated_anonymously', 'boolean');

        $this->options = $resolver->resolve($options);
    }

    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->options[$offset]) ? $this->options[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Setting options via array access is not supported.');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Removing options via array access is not supported.');
    }
}
