<?php

namespace Wolif\Validate\Necessity;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class RequiredWith_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        $condition = false;
        foreach ($params as $another_field) {
            if ($this->exists($another_field, $input)) {
                $condition = true;
                break;
            }
        }

        if ($condition) {
            if ($this->exists($field, $input)) {
                return Result::success();
            }
            $other_fields = implode(',', $params);
            return Result::failed("params [{$field}] is necessary if any one of fields[{$other_fields}] exists", 'required_with');
        }

        return Result::quit();
    }
}
