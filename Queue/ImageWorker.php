<?php
// And our libraries
include __DIR__ . "/../Exec/Essential.php";

$beanstalk = new Socket_Beanstalk();
$beanstalk->connect();
$beanstalk->watch('media');

while (true) {
   $task = $beanstalk->reserve();
   $job = json_decode($task['body']);

   $medid = $job->medid;
   
   print_r($job);
   $response = MediaProcessor::generateMediaVersions($medid);
   print_r($response); 

   $beanstalk->delete($task['id']);
}
$beanstalk->disconnect();
