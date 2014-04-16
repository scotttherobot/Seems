<?php
// And our libraries
include __DIR__ . "/Essential.php";

$filesystem = MediaManager::getFilesystem();

print_r($filesystem->keys());
