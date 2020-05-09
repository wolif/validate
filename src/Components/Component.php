<?php

namespace Wolif\Validate\Components;

abstract class Component
{
    abstract public function confirm($field, $input, ...$params);

    protected function exists($field, $input)
    {
        return array_key_exists($field, $input);
    }

    protected static $extends = [];

    public static function extends($name, callable $callback, $cover = true)
    {
        if (array_key_exists($name, static::$extends) && !$cover) {
            return;
        }

        static::$extends[$name] = $callback;
    }

    public function __call($name, $arguments)
    {
        $class = static::class;
        if (method_exists($class, $name)) {
            return $this->$name(...$arguments);
        }

        if (array_key_exists($name, static::$extends)) {
            return static::$extends[$name](...$arguments);
        }

        throw new \BadMethodCallException("method [{$name}] not found in class [{$class}]!");
    }
}
