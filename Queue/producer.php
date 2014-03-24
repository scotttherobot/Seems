<?php

require_once('../3P/php-beanstalk/src/Socket/Beanstalk.php');

$beanstalk = new Socket_Beanstalk();

$beanstalk->connect();

$data = [
   'event' => "New Message",
   'threadid' => 1,
   ];

$beanstalk->put(23, 0, 500, json_encode($data));

$beanstalk->disconnect();
