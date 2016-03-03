<?php

ini_set('display_errors', 1);

if (is_dir(__DIR__ . "/../vendor")) {
    $dir = __DIR__ . "/../vendor";
} else {
    $dir = __DIR__ . "/../../..";
}
$dir = realpath($dir);
require_once $dir . "/autoload.php";
