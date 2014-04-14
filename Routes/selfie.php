<?php

$app->get('/selfie/', function () use ($app) {
   $per = SettingsLib::get('selfie-wall-enabled') ? ['public'] : ['user'];
   $page = new Page($app, $per);

   $galleryId = SettingsLib::get('selfie-gallery-id');
   if (!$galleryId) {
      $page->error("No gallery set!");
   }

   $gallery = new Gallery($galleryId);
   $page->addData([
      'gallery' => $gallery,
   ]);

   $page->addRemoteScript('/3P/reveal/jquery.reveal.js');
   $page->addRemoteStyle('/3P/reveal/reveal.css');

   $page->addRemoteScript('/3P/webcam/webcam.min.js');
   $page->addTemplateSet("selfie");
   $page->addTemplateSet("galleryPage");

   $page->enableNav();

   $page->render();
});
