<?php
// And our libraries
include __DIR__ . "/Essential.php";

$medids = DB::queryFirstColumn("
   SELECT medid
   FROM media");
MediaManager::queueVersionGeneration($medids);

