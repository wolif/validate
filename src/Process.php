<?php

namespace Wolif\Validate;

use Wolif\Validate\Components\Components;

class Process
{
    //is the field necessary,  quit the validation when it's not necessary and the field not exists
    private $necessity       = ['sometimes'];

    //the values should ignore, quit the validation if the value equal
    private $ignore_values   = [];

    //the data type [int number string array object ...]
    private $type            = 'any';

    //the validation processes based on data type
    private $processes       = [];

    public function __construct($validation_processes)
    {
        //resolve the processes
        $tmp = [];
        if (is_string($validation_processes)) {//data string like "required|int|min:1|max:10"
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
        if (array_key_exists($process[0], Components::necessities())) {
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
        if (array_key_exists($process[0], Components::types())) {
            $this->type = $process[0];
        }
        
        foreach ($tmp as $process) {
            $this->processes[] = $process;
        }
    }

    private function exec($field, $input)
    {
        //validate necessity
        $necessity = Components::get(array_shift($this->necessity));
        $result = $necessity->confirm($field, $input, ...$this->necessity);
        if ($result->code  != Result::SUCCESS) {
            return $result;
        }

        //validate ignore values
        if (in_array($field, $this->ignore_values)) {
            return Result::quit();
        }

        //validate date type
        $typeComponent = Components::get($this->type);
        $result = $typeComponent->confirm($field, $input);
        if ($result->code  != Result::SUCCESS) {
            return $result;
        }

        //validate date through type component
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
        if (in_array('*', explode('.', $field))) {//validate value array
            foreach ($input[$field] as $value) {
                $result = $this->exec($field, [$field => $value]);
                if ($result->code != Result::SUCCESS) {
                    return $result;
                }
            }
            return Result::success();
        } else {//validate value alone
            return $this->exec($field, $input);
        }
    }
}
