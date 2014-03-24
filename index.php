<?php
// Get Slim in here
//require '3P/Slim/Slim/Slim.php';
require 'vendor/autoload.php';
// Beanstalk
//require_once('3P/php-beanstalk/src/Socket/Beanstalk.php');
// And our libraries
foreach (glob("Libs/*.php") as $filename) {
   include $filename;
}
// And objects
foreach (glob("Objects/*.php") as $filename) {
   include $filename;
}

//\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->config(['templates.path' => './Templates']);

// Configure the DB singleton
DB::$user = 'root';
DB::$password = 'anncoulter';
DB::$dbName = 'scottvanderlinddotcom';
DB::$host = 'localhost';

//$user = User::auth();
//AuthLib::init($app, $user);

foreach (glob("Routes/*.php") as $filename) {
   include $filename;
}
// And the 0.1 api
$app->group('/0.1', function() use ($app) {
   foreach (glob("0.1/*.php") as $filename) {
      include $filename;
   }
});


$app->run();
