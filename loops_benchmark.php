<?php

run_benchmarks();

function benchmark($closure, array $data, int $number_of_cycles)
{
    $start_time = microtime(true);
    echo "Function '{$closure}': ";
    for ($i = 0; $i < $number_of_cycles; $i++) {
        $closure($data);
//        echo '<pre>'.print_r($data, true).'</pre>';
    }
    $time = microtime(true) - $start_time;
    echo "{$time}" . PHP_EOL;
}

function run_benchmarks()
{
    $cycles_count = 10000;
    $items_count = 10000;
    $functions = [
        'test_foreach',
        'test_for',
        'test_while',
        'test_array_walk',
        'test_array_walk_named',
        'test_list_each',
    ];
    echo "Number of cycles: {$cycles_count}" . PHP_EOL;
    echo "Number of elements: {$items_count}" . PHP_EOL;
    foreach ($functions as $f) {
        $data = array_fill(0, $items_count, 1);
        benchmark($f, $data, $cycles_count);
    }
}

function named_function(&$value, $key)
{
    $value += 1;
}

function test_array_walk_named(array &$data)
{
    $f_name = 'named_function';
    array_walk($data, $f_name);
}

function test_array_walk(array &$data)
{
    array_walk($data, function (&$value, $key) {
        $value += 1;
    });
}

function test_foreach(array &$data)
{
    foreach ($data as $key => &$value) {
        $value += 1;
    }
}

function test_while(array &$data)
{
    $i = 0;
    while (isset($data[$i])) {
        $data[$i++] += 1;
    }
}

function test_for(array &$data)
{
    for ($i = 0; $i < count($data); $i++) {
        $data[$i] += 1;
    }
}

function test_list_each(array &$data)
{
    while (list($key, $value) = each($data)) {
        $data[$key] += 1;
    }
}