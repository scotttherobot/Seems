<?php

$app->get('/selfie/', function () use ($app) {
   $per = SettingsLib::get('selfie-wall-enabled') ? ['public'] : ['user'];
   $page = new Page($app, $per);

   $page->addRemoteScript('/3P/webcam/webcam.min.js');

   $page->addTemplate('selfie.phtml');
   $page->addScript('selfie.js');
   $page->addStyle('selfie.css');

   $page->enableNav();

   $page->render();
});
