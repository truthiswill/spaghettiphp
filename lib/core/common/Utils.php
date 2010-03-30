<?php

function pr($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

function dump($data) {
    pr(var_export($data, true));
}

function array_unset(&$array = array(), $index) {
    if(array_key_exists($index, $array)):
        $item = $array[$index];
        unset($array[$index]);
        return $item;
    endif;
    return null;
}

function can_call_method(&$object, $method) {
    if(method_exists($object, $method)):
        $method = new ReflectionMethod($object, $method);
        return $method->isPublic();
    endif;
    return false;
}

function array_range($min, $max) {
    $result = array();
    for($i = $min; $i < $max + 1; $i++):
        $result[$i] = $i;
    endfor;
    return $result;
}