<?php

namespace Wolif\Validate\Components\Type;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class Number_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        if (is_numeric($input[$field])) {
            return Result::success();
        }
        
        return Result::failed("param [{$field}] must be an integer", 'number');
    }

    public function min($field, $input, ...$params)
    {
        return $this->gte($field, $input, ...$params);
    }

    public function max($field, $input, ...$params)
    {
        return $this->lte($field, $input, ...$params);
    }

    public function gt($field, $input, ...$params)
    {
        list($value) = $params;
        if ($input[$field] > $value) {
            Result::success();
        }

        return Result::failed("param [{$field}] must > {$value}", 'gt');
    }

    public function gte($field, $input, ...$params)
    {
        list($value) = $params;
        if ($input[$field] >= $value) {
            Result::success();
        }

        return Result::failed("param [{$field}] must >= {$value}", 'gte');
    }

    public function lt($field, $input, ...$params)
    {
        list($value) = $params;
        if ($input[$field] < $value) {
            Result::success();
        }

        return Result::failed("param [{$field}] must < {$value}", 'lt');
    }

    public function lte($field, $input, ...$params)
    {
        list($value) = $params;
        if ($input[$field] <= $value) {
            Result::success();
        }

        return Result::failed("param [{$field}] must <= {$value}", 'lte');
    }

    public function in($field, $input, ...$params)
    {
        list($values) = $params;
        is_string($values) && $values = explode(',', $values);

        if (in_array($input[$field], $values)) {
            return Result::success();
        }
        
        $values = implode(',', $values);
        return Result::failed("the value of param [{$field}] must in [{$values}]", 'in');
    }

    public function nin($field, $input, ...$params)
    {
        list($values) = $params;
        is_string($values) && $values = explode(',', $values);

        if (!in_array($input[$field], $values)) {
            return Result::success();
        }
        
        $values = implode(',', $values);
        return Result::failed("the value of param [{$field}] must not in [{$values}]", 'nin');
    }

    public function cmpGt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] must exists and it must be an integer", 'cmpGt');
        }

        if ($input[$field] > $input[$another_field]) {
            return Result::success();
        }

        return Result::failed("param [{$field}] must > [{$another_field}]", 'cmpGt');
    }

    public function cmpGte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] must exists and it must be an integer", 'cmpGte');
        }

        if ($input[$field] >= $input[$another_field]) {
            return Result::success();
        }

        return Result::failed("param [{$field}] must >= [{$another_field}]", 'cmpGte');
    }

    public function cmpLt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] must exists and it must be an integer", 'cmpLt');
        }

        if ($input[$field] < $input[$another_field]) {
            return Result::success();
        }

        return Result::failed("param [{$field}] must < [{$another_field}]", 'cmpLt');
    }

    public function cmpLte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] must exists and it must be an integer", 'cmpLte');
        }

        if ($input[$field] >= $input[$another_field]) {
            return Result::success();
        }

        return Result::failed("param [{$field}] must >= [{$another_field}]", 'cmpLte');
    }
}
