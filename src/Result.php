<?php

namespace Wolif\Validate;

use \stdClass;

class Result
{
    const FAILED = 0;
    const SUCCESS = 1;

    const QUIT = 10;

    public static function failed($hint, $rule_name = '')
    {
        $result = new stdClass();
        $result->code = static::FAILED;
        $result->hint = $hint;
        $result->rule_name = $rule_name;

        return $result;
    }

    public static function success($rule_name = '')
    {
        $result = new stdClass();
        $result->code = static::SUCCESS;
        $result->hint = '';
        $result->rule_name = $rule_name;

        return $result;
    }

    public static function quit($rule_name = '')
    {
        $result = new stdClass();
        $result->code = static::QUIT;
        $result->hint = '';
        $result->rule_name = $rule_name;

        return $result;
    }
}
