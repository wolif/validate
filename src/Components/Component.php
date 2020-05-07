<?php

namespace Wolif\Validate\Components;

abstract class Component
{
    abstract public function confirm($field, $input, ...$params);

    protected function exists($field, $input)
    {
        return array_key_exists($field, $input);
    }

    public function __call($name, $arguments)
    {
        if (method_exists(static::class, $name)) {
            return $this->$name(...$arguments);
        }
        throw new \BadMethodCallException("method [{$name}] not found!");
    }
}
