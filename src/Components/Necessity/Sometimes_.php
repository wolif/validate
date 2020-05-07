<?php

namespace Wolif\Validate\Necessity;

use Wolif\Validate\Components\Component;
use Wolif\Validate\Result;

class Sometimes_ extends Component
{
    public function confirm($field, $input, ...$params)
    {
        if ($this->exists($field, $input)) {
            return Result::success();
        }
        return Result::quit();
    }
}
