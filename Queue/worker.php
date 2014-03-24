<?php
// Autoload dependencies.
include '../vendor/autoload.php';

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

$gcmApiKey = "AIzaSyCmsCJ334CHytuFIOW97DXLGpDs_G0jueQ";

$beanstalk = new Socket_Beanstalk();
$beanstalk->connect();

while (true) {
   $job = $beanstalk->reserve();
   $data = json_decode($job['body']);

   $subs = DB::query("
      SELECT s.uuid, s.type, t.name
      FROM subscriptions s
      JOIN participants p USING (`userid`)
      JOIN threads t ON (p.`threadid` = t.`id`)
      WHERE s.notifications = 'ON'
       AND p.notifications = 'ON'
       AND p.status != 'LEFT'
       AND p.threadid = %i
       AND s.userid != %i
       AND s.type = 'GCM'", $data->threadid, $data->userid);

   $registrationIds = [];
   foreach ($subs as $sub) {
      print($sub['uuid'] . "\n");
      $registrationIds[] = $sub['uuid'];
   }
   $messageData = [
      'title' => $data->title,
      'message' => $data->message,
      'threadid' => $data->threadid,
      'threadname' => $subs[0]['name'],
   ];

   $response = PushLib::sendNotification(
      $gcmApiKey,
      $registrationIds,
      $messageData);
   print_r($response);
   print("\n");


   $beanstalk->delete($job['id']);
}

$beanstalk->disconnect();

