<?php
// And our libraries
foreach (glob("../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob("../Objects/*.php") as $filename) {
   include $filename;
}


DB::$user = 'root';
DB::$password = 'anncoulter';
DB::$dbName = 'chat';
DB::$host = 'localhost';

print_r(DB::query("select * from subscriptions"));
