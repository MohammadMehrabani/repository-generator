<?php

if (! function_exists('suffix')) {
    /**
     * Adding string to end of target's value.
     *
     * @param $target
     * @param $suffix
     * @return string
     */
    function suffix($target, $suffix)
    {
        return $target.$suffix;
    }
}
