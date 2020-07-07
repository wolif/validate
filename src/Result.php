<?php

namespace Wolif\Validate;

use \stdClass;

class Result
{
    const FAILED = 0;
    const SUCCESS = 1;

    const QUIT = 10;

    private static function result($code, $hint, $rule_name)
    {
        $result = new stdClass();
        $result->code = $code;
        $result->hint = $hint;
        $result->rule_name = $rule_name;

        return $result;
    }

    public static function failed($hint, $rule_name = '')
    {
        return static::result(static::FAILED, $hint, $rule_name);
    }

    public static function success($rule_name = '')
    {
        return static::result(static::SUCCESS, '', $rule_name);
    }

    public static function quit($rule_name = '')
    {
        return static::result(static::QUIT, '', $rule_name);
    }
}
