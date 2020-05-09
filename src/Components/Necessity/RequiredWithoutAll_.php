<?php

namespace Wolif\Validate\Components\Necessity;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class RequiredWithoutAll_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        $condition = true;
        foreach ($params as $another_field) {
            if ($this->exists($another_field, $input)) {
                $condition = false;
                break;
            }
        }

        if ($condition) {
            if ($this->exists($field, $input)) {
                return Result::success();
            }
            $other_fields = implode(',', $params);
            return Result::failed("param [{$field}] is necessary when fields[{$other_fields}] not exists", 'required_withoutAll');
        }
        
        return Result::quit();
    }
}
