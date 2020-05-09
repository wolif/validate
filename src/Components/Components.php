<?php

namespace Wolif\Validate\Components;

use InvalidArgumentException;

class Components
{
    const necessity = 'necessity';
    const type = 'type';

    const ns_necessity = 'Wolif\\Validate\\Components\\Necessity\\';
    const ns_type      = 'Wolif\\Validate\\Components\\Type\\';

    private static $types = [
        'any'    => Components::ns_type . "Any_",
        'array'  => Components::ns_type . "Array_",
        'int'    => Components::ns_type . "Int_",
        'number' => Components::ns_type . "Number_",
        'object' => Components::ns_type . "Object_",
        'string' => Components::ns_type . "String_",
    ];

    private static $necessities = [
        'required'             => Components::ns_necessity . 'Required_',
        'required_if'          => Components::ns_necessity . 'RequiredIf_',
        'required_unless'      => Components::ns_necessity . 'RequiredUnless_',
        'required_with'        => Components::ns_necessity . 'RequiredWith_',
        'required_withAll'     => Components::ns_necessity . 'RequiredWithAll_',
        'required_without'     => Components::ns_necessity . 'RequiredWithout_',
        'required_without_all' => Components::ns_necessity . 'RequiredWithoutAll_',
        'sometimes'            => Components::ns_necessity . 'Sometimes_',
    ];

    public static function types()
    {
        return static::$types;
    }

    public static function necessities()
    {
        return static::$necessities;
    }

    private static $components = [];

    public static function get($name)
    {
        if (!array_key_exists($name, static::$components)) {
            if (array_key_exists($name, static::$necessities)) {
                static::$components[$name] = new static::$necessities[$name];
            } elseif (array_key_exists($name, static::$types)) {
                static::$components[$name] = new static::$types[$name];
            } else {
                throw new InvalidArgumentException("Validate element [{$name}] not defined!");
            }
        }

        return static::$components[$name];
    }

    public static function set($type, $name, $component, $cover = true)
    {
        switch ($type) {
            case Components::necessity:
                static::setNecessityComponent($name, $component, $cover);
            break;
            case Components::type:
                static::setTypeComponent($name, $component, $cover);
            break;
            default:break;
        }
    }

    public static function setNecessityComponent($name, $component, $cover = true)
    {
        if (array_key_exists($name, static::$necessities) && !$cover) {
            return;
        }

        if (is_string($component) && class_exists($component)) {
            static::$necessities[$name] = $component;
        } elseif (is_object($component) && $component instanceof Component) {
            static::$necessities[$name] = get_class($component);
            static::$components[$name] = $component;
        }
    }

    public static function setTypeComponent($name, $component, $cover = true)
    {
        if (array_key_exists($name, static::$types) && !$cover) {
            return;
        }

        if (is_string($component) && class_exists($component)) {
            static::$types[$name] = $component;
        } else {
            static::$types[$name] = get_class($component);
            static::$components[$name] = $component;
        }
    }
}
