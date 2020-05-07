<?php

namespace Wolif\Validate\Necessity;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class RequiredIf_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        list($another_field, $value) = $params;
        if ($this->exists($another_field, $input) && $input[$another_field] == $value) {
            if ($this->exists($field, $input)) {
                return Result::success();
            } else {
                return Result::failed("params [{$field}] is required when field [{$another_field}] == {$value}", 'required_if');
            }
        }

        return Result::quit();
    }
}
