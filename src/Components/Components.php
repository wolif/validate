<?php

namespace Wolif\Validate\Components;

use InvalidArgumentException;

class Components
{
    private static $components = [];

    public static function get($name)
    {
        if (!array_key_exists($name, static::$components)) {
            $necessity_ns = 'Wolif\\Validate\\Component\\Necessity\\';
            $type_ns = 'Wolif\\Validate\\Component\\Type\\';

            $class_name = '';
            foreach (explode('_', $name) as $piece) {
                $class_name .= ucfirst($piece);
            }
            $class_name .= '_';

            if (class_exists($class = $necessity_ns . $class_name) || class_exists($class = $type_ns . $class_name)) {
                static::$components[$name] = new $class;
            } else {
                throw new InvalidArgumentException("Validate element [{$name}] not found!");
            }
        }

        return static::$components[$name];
    }
}
