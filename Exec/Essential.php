<?php

include __DIR__ . "/../vendor/autoload.php";

DB::$user = 'root';
DB::$password = 'anncoulter';
DB::$dbName = 'scottvanderlinddotcom';
DB::$host = 'localhost';

// And our libraries
foreach (glob(__DIR__ . "/../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob(__DIR__ . "/../Objects/*.php") as $filename) {
   include $filename;
}
