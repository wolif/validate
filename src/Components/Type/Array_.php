<?php

namespace Wolif\Validate\Type;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class Array_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        if (!is_array($input[$field])) {
            return Result::failed("param [{$field}] must be an array", 'array');
        }

        if (array_keys($input[$field]) == range(0, count($input[$field]))) {
            return Result::success();
        }

        return Result::failed("param [{$field}] must be an array", 'array');
    }

    public function sizeMin($field, $input, ...$params)
    {
        list($min) = $params;
        if (count($input[$field]) >= $min) {
            return Result::success();
        }
        return Result::failed("the array length of param [{$field}] must >= {$min}", 'sizeMin');
    }

    public function sizeMax($field, $input, ...$params)
    {
        list($max) = $params;
        if (count($input[$field]) <= $max) {
            return Result::success();
        }
        return Result::failed("the array length of param [{$field}] must <= {$max}", 'sizeMax');
    }

    public function cmpLenGt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenGt');
        }

        if (count($input[$field]) > count($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the array length of param [{$field} must > [{$another_field}]]", 'cmpLenGt');
    }

    public function cmpLenGte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenGte');
        }

        if (count($input[$field]) >= count($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the array length of param [{$field} must >= [{$another_field}]]", 'cmpLenGte');
    }

    public function cmpLenLt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenLt');
        }

        if (count($input[$field]) < count($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the array length of param [{$field} must < [{$another_field}]]", 'cmpLenLt');
    }

    public function cmpLenLte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenLte');
        }

        if (count($input[$field]) <= count($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the array length of param [{$field} must <= [{$another_field}]]", 'cmpLenLte');
    }
}
