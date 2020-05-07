<?php

namespace Wolif\Validate\Type;

use InvalidArgumentException;
use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class String_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        if (is_string($input[$field])) {
            return Result::success();
        }
        
        return Result::failed("param [{$field}] must be an string", 'string');
    }

    public function sizeMin($field, $input, ...$params)
    {
        list($min) = $params;
        if (mb_strlen($input[$field]) >= $min) {
            return Result::success();
        }
        return Result::failed("the string length of param [{$field}] must >= {$min}", 'sizeMin');
    }

    public function sizeMax($field, $input, ...$params)
    {
        list($max) = $params;
        if (mb_strlen($input[$field]) <= $max) {
            return Result::success();
        }
        return Result::failed("the string length of param [{$field}] must <= {$max}", 'sizeMax');
    }

    public function regex($field, $input, ...$params)
    {
        list($regex) = $params;
        if (preg_match($regex, $input[$field]) == 1) {
            return Result::success();
        }

        return Result::failed("param [{$field}] format error", 'regex');
    }

    private $formats = [
        'ipv4' => '',
        'email' => '',
    ];
    public function format($field, $input, ...$params)
    {
        list($regType) = $params;
        if (!array_key_exists($regType, $this->formats)) {
            throw new InvalidArgumentException("format [{$regType}] not defined");
        }

        return $this->regex($field, $input, $this->formats[$regType]);
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

    public function cmpLenGt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenGt');
        }

        if (mb_strlen($input[$field]) > mb_strlen($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the string length of param [{$field} must > [{$another_field}]]", 'cmpLenGt');
    }

    public function cmpLenGte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenGte');
        }

        if (mb_strlen($input[$field]) >= mb_strlen($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the string length of param [{$field} must >= [{$another_field}]]", 'cmpLenGte');
    }

    public function cmpLenLt($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenLt');
        }

        if (mb_strlen($input[$field]) < mb_strlen($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the string length of param [{$field} must < [{$another_field}]]", 'cmpLenLt');
    }

    public function cmpLenLte($field, $input, ...$params)
    {
        list($another_field) = $params;
        if (!$this->exists($another_field, $input)) {
            return Result::failed("param [{$another_field}] is necessary", 'cmpLenLte');
        }

        if (mb_strlen($input[$field]) <= mb_strlen($input[$another_field])) {
            return Result::success();
        }

        return Result::failed("the string length of param [{$field} must <= [{$another_field}]]", 'cmpLenLte');
    }
}
