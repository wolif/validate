<?php

namespace Wolif\Validate;

use Wolif\Validate\Components\Components;

class Process
{
    private $necessity       = ['sometimes'];
    private $ignore_values   = [];
    private $type            = 'any';
    private $processes       = [];

    public function __construct($validation_processes)
    {
        $tmp = [];
        if (is_string($validation_processes)) {
            foreach (explode('|', $validation_processes) as $process) {
                $tmp[] = explode(':', $process);
            }
        } elseif (is_array($validation_processes)) {
            foreach ($validation_processes as $process) {
                if (is_string($process)) {
                    $tmp[] = [$process];
                } elseif (is_array($process)) {
                    $tmp[] = $process;
                }
            }
        }

        $process = array_shift($tmp);

        //necessity default: sometimes
        if (in_array($process[0], Enum::necessities())) {
            $this->necessity = $process;
            $process         = array_shift($tmp);
        }
        //ignore default: []
        if ($process[0] == 'ignore') {
            $map = ['zero' => 0, 'null' => null, 'empty' => ''];
            $ignore_values = $process ?? [];
            is_string($ignore_values) && $ignore_values = explode(',', $ignore_values);
            $this->ignore_values = [];
            foreach ($ignore_values as $val) {
                $this->ignore_values[] = array_key_exists($val, $map) ? $map[$val] : $val;
            }

            $process = array_shift($tmp);
        }
        //type default: any
        if (in_array($process[0], Enum::types())) {
            $this->type = $process[0];
        }
        
        foreach ($tmp as $process) {
            $this->processes[] = $process;
        }
    }

    private function exec($field, $input)
    {
        $necessity = Components::get(array_shift($this->necessity));
        $result = $necessity->confirm($field, $input, ...$this->necessity);
        if ($result->code  != Result::SUCCESS) {
            return $result;
        }

        if (in_array($field, $this->ignore_values)) {
            return Result::quit();
        }

        $typeComponent = Components::get($this->type);
        $result = $typeComponent->confirm($field, $input);
        if ($result->code  != Result::SUCCESS) {
            return $result;
        }

        foreach ($this->processes as $process) {
            $method = array_shift($process);
            $result = $typeComponent->$method($field, $input, ...$process);
            if ($result->code != Result::SUCCESS) {
                return $result;
            }
        }

        return Result::success();
    }

    public function execute($field, array $input)
    {
        if (in_array('*', explode('.', $field))) {
            foreach ($input[$field] as $value) {
                $result = $this->exec($field, [$field => $value]);
                if ($result->code != Result::SUCCESS) {
                    return $result;
                }
            }
            return Result::success();
        } else {
            return $this->exec($field, $input);
        }
    }
}
