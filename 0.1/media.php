<?php

$app->get('/media/', function () {
   $res = new APIResponse(['user']);
   $media = new MediaManager($res->userid);

   $res->addData(['media' => $media->media()]);

   $res->respond();
   
});

$app->post('/media/', function () {
   $res = new APIResponse(['user']);
   $media = new MediaManager($res->userid);

   if($uploaded = $media->upload($_FILES)) {
      $res->addData(['uploaded' => $uploaded]);
   }
   else {
      $res->error("There was a problem uploading.");
   }

   $res->respond();
});

$app->get('/media/:medid/', function ($medid) {
   $res = new APIResponse(['user']);
   $media = new MediaManager($res->userid);

   $res->addData(['meta' => $media->meta($medid)]);

   $res->respond();
   
});
