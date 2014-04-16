<?php
// And our libraries
include __DIR__ . "/Essential.php";

array_shift($argv);
$medids = $argv;

if (!count($medids)) {
   print("Usage: ". __FILE__ ." <medid>...\n");
   die();
}

MediaManager::queueVersionGeneration($medids);


