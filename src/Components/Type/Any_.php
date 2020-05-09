<?php

namespace Wolif\Validate\Components\Type;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class Any_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        return Result::success();
    }
}
