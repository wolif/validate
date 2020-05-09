<?php

namespace Wolif\Validate;

class EventEmitter
{
    private static $emitters = [];

    public static function off($event = null, callable $callback = null)
    {
        if (is_null($event)) {
            static::$emitters = null;
        } else {
            if (is_null($callback)) {
                static::$emitters[$event] = null;
            } else {
                if (array_key_exists($event, static::$emitters)) {
                    foreach (array_keys(static::$emitters[$event], $callback) as $key) {
                        unset(static::$emitters[$event][$key]);
                    }
                    if (!static::$emitters[$event]) {
                        unset(static::$emitters[$event]);
                    }
                }
            }
        }
    }

    public static function on($event, callable $callback)
    {
        if (!array_key_exists($event, static::$emitters)) {
            static::$emitters[$event] = [];
        }

        static::$emitters[$event][] = $callback;
    }

    public static function once($event, callable $callback)
    {
        $once = function (...$params) use ($event, $callback, &$once) {
            $callback(...$params);
            static::off($event, $once);
        };
        static::on($event, $once);
    }

    public static function emit($event, ...$params)
    {
        if (array_key_exists($event, static::$emitters)) {
            foreach (static::$emitters[$event] as $callback) {
                $callback(...$params);
            }
        }
    }
}
