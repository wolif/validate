<?php

namespace Wolif\Validate;

class Validator
{
    private $hints = [];
    private $processes = [];
    private $result = [];

    private function init()
    {
        $this->hints = [];
        $this->processes = [];
        $this->result = [];
    }

    public function set($rules, $hints)
    {
        $this->init();

        //format the customized hints
        foreach ($hints as $filed => $hint) {
            if (is_string($hint)) {
                $this->hints[$filed] = $hint;
            } elseif (is_array($hint)) {
                foreach ($hint as $rule_name => $msg) {
                    if (is_string($msg)) {
                        $this->hints["{$filed}.{$rule_name}"] = $msg;
                    }
                }
            }
        }
        foreach ($rules as $field => $rule) {
            $this->processes[$field] = new Process($rule);
        }

        return $this;
    }

    public function validate($input)
    {
        $this->result = [];

        //resolve the field-value pairs will be validate 
        $input_use = [];
        foreach ($this->processes as $field => $process) {
            if (wd_array_key_exists($field, $input)) {
                $input_use[$field] = wd_array_get($input, $field);
            }
        }

        //execute the validations
        foreach ($this->processes as $field => $process) {
            $result = $process->execute($field, $input_use);
            if ($result->code == Result::FAILED) {
                //if the customized hint defined, show it
                if (array_key_exists($hint_name = "{$field}.{$result->rule_name}", $this->hints)) {
                    $result->hint = $this->hints[$hint_name];
                }
            }
            $this->result[$field] = $result;
        }
        return $this->result;
    }

    public function lastResult()
    {
        return $this->result;
    }

    //-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
    private static $validator;

    public static function v($input, $rules, $hints = [])
    {
        if (!static::$validator) {
            static::$validator = new Validator();
        }

        return static::$validator->set($rules, $hints)->validate($input);
    }

    public static function lastResults()
    {
        return static::$validator->lastResult();
    }
}
