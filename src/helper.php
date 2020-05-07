<?php

if (!function_exists('wd_array_key_exists')) {
    function wd_array_key_exists($key, array $array, $delimiter = '.', $star_as_any_ele = true)
    {
        $pieces = explode($delimiter, $key);
        $nowSearch = &$array;

        if (in_array('*', $pieces) && $star_as_any_ele) {
            if (array_count_values($pieces)['*'] > 1) {
                throw new \InvalidArgumentException("the number of '*' beyond 1");
            }

            $bucket = null;
            foreach ($pieces as $piece) {
                if ($piece == '*') {
                    if (is_array($nowSearch)) {
                        $bucket = $nowSearch;
                    } else {
                        return false;
                    }
                } else {
                    if (is_null($bucket)) {
                        if (is_array($nowSearch) && array_key_exists($piece, $nowSearch)) {
                            $nowSearch = &$nowSearch[$piece];
                        } else {
                            return false;
                        }
                    } else {
                        $bucket_tmp = [];
                        foreach ($bucket as $search) {
                            if (is_array($search) && array_key_exists($piece, $search)) {
                                $bucket_tmp[] = $search[$piece];
                            } else {
                                return false;
                            }
                        }
                        $bucket = $bucket_tmp;
                    }
                }
            }
        } else {
            foreach ($pieces as $piece) {
                if (is_array($nowSearch) && array_key_exists($piece, $nowSearch)) {
                    $nowSearch = &$nowSearch[$piece];
                } else {
                    return false;
                }
            }
        }

        return true;
    }
}

if (!function_exists('wd_array_get')) {
    function wd_array_get(array $array, $key, $delimiter = '.', $star_as_any_ele = true)
    {
        $ret = null;

        $pieces = explode($delimiter, $key);
        $nowSearch = &$array;

        if (in_array('*', $pieces) && $star_as_any_ele) {
            if (array_count_values($pieces)['*'] > 1) {
                throw new \InvalidArgumentException("the number of '*' beyond 1");
            }

            $bucket = null;
            foreach ($pieces as $piece) {
                if ($piece == '*') {
                    if (is_array($nowSearch)) {
                        $bucket = array_values($nowSearch);
                    } else {
                        throw new \InvalidArgumentException("key [{$key}] is not an array");
                    }
                } else {
                    if (is_null($bucket)) {
                        if (is_array($nowSearch) && array_key_exists($piece, $nowSearch)) {
                            $nowSearch = $nowSearch[$piece];
                        } else {
                            return null;
                        }
                    } else {
                        $tmp_bucket = [];
                        foreach ($bucket as $search) {
                            if (is_array($search) && array_key_exists($piece, $search)) {
                                $tmp_bucket[] = $search[$piece];
                            } else {
                                $tmp_bucket[] = null;
                            }
                        }
                        $bucket = $tmp_bucket;
                    }
                }
            }

            $ret = is_null($bucket) ? $nowSearch : $bucket;
        } else {
            foreach ($pieces as $piece) {
                if (is_array($nowSearch) && array_key_exists($piece, $nowSearch)) {
                    $nowSearch = &$nowSearch[$piece];
                } else {
                    return null;
                }
            }
            $ret = $nowSearch;
        }

        return $ret;
    }
}
