<?php
// And our libraries
include __DIR__ . "/../Exec/Essential.php";

$response = MediaManager::generateMediaVersions($argv[1]);
print_r($response); 
