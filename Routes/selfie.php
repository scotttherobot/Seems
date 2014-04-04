<?php

$app->get('/selfie/', function () use ($app) {
   $page = new Page($app, ['user']);

   $page->addRemoteScript('/3P/webcam/webcam.min.js');

   $page->addTemplate('selfie.phtml');
   $page->addScript('selfie.js');
   $page->addStyle('selfie.css');

   $page->enableNav();

   $page->render();
});
