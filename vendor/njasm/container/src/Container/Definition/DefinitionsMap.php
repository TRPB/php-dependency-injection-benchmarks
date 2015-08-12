<?php

namespace Njasm\Container\Definition;

class DefinitionsMap extends \ArrayObject
{
    public function add(Definition $definition)
    {
        $this[$definition->getKey()] = $definition;
    }

    public function has($key)
    {
        return isset($this[$key]);
    }

    public function get($key)
    {
        return $this[$key];
    }
}
