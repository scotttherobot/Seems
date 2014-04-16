<?php
// And our libraries
include __DIR__ . "/Essential.php";

$response = MediaProcessor::generateMediaVersions($argv[1]);
print_r($response); 
