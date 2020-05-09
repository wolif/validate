<?php

namespace Wolif\Validate\Components\Type;

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

    public function json($field, $input, ...$params)
    {
        if (null == ($data = json_decode($input[$field], true))) {
            return Result::failed("param [{$field}] json format necessary", 'json');
        }

        if ($params) {
            if (is_string($params[0])) {
                $params[0] = explode(',', $params[0]);
            }
            foreach ($params[0] as $key) {
                if (!array_key_exists($key, $data)) {
                    return Result::failed("param [{$field}] json string need field [{$key}]", 'json');
                }
            }
        }

        return Result::success();
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

    private static $formats = [
        'ipv4' => "#^(1\d{2}|2[0-4]\d|25[0-5]|[1-9]\d|[1-9])\."
                . "(1\d{2}|2[0-4]\d|25[0-5]|[1-9]\d|\d)\."
                . "(1\d{2}|2[0-4]\d|25[0-5]|[1-9]\d|\d)\."
                . "(1\d{2}|2[0-4]\d|25[0-5]|[1-9]\d|\d)$#",
        'email' => "/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/",
    ];

    public static function setFormat($name, $regex, $cover = true)
    {
        if (array_key_exists($name, static::$formats) && !$cover) {
            return;
        }
        static::$formats[$name] = $regex;
    }

    public function format($field, $input, ...$params)
    {
        list($regType) = $params;
        if (!array_key_exists($regType, static::$formats)) {
            throw new InvalidArgumentException("format [{$regType}] not defined");
        }

        return $this->regex($field, $input, static::$formats[$regType]);
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
