<?php

namespace Wolif\Validate\Components\Type;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class Object_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        if (!is_array($input[$field])) {
            return Result::failed("param [{$field}] must be an object", 'object');
        }

        if (array_keys($input[$field]) != range(0, count($input[$field]))) {
            return Result::success();
        }
        
        return Result::failed("param [{$field}] must be an object", 'object');
    }

    public function contain($field, $input, ...$params)
    {
        list($child_fields) = $params;
        is_string($child_fields) && $child_fields = explode(',', $child_fields);
        foreach ($child_fields as $child_field) {
            if (!array_key_exists($child_field, $input[$field])) {
                return Result::failed("param [{$field}] must contains child field [{$child_field}]", 'contain');
            }
        }

        return Result::success();
    }
}
