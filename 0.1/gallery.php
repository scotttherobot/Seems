<?php

$app->get('/media/galleries/', function () {
   $res = new APIResponse(['user']);
   $galleries = Gallery::all();

   $res->addData(['galleries' => $galleries]);

   $res->respond();
});

$app->post('/media/galleries/', function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['name']);

   $gallery = Gallery::create($res->userid, $params['name']);

   $res->addData($gallery->getRow());

   $res->respond();
});

$app->get('/media/galleries/:id/', function ($id) {
   $res = new APIResponse(['user']);
   $gallery = new Gallery($id);

   $row = $gallery->getRow();
   $items = $gallery->getEntries();

   $res->addData($row, 'No such gallery!');
   $res->addData(['media' => $items]);

   $res->respond();
});

$app->post('/media/galleries/:id/', function ($id) {
   $res = new APIResponse(['user']);
   $gallery = new Gallery($id);

   $params = $res->params($_POST, ['medid']);
   $caption = idx($_POST, "caption");

   $gallery->addMedia($params['medid'], $caption);

   $res->addData(['gallery' => $gallery->getEntries()]);

   $res->respond();
});

/**
 * A custom endpoint to allow uploading to the sacrificial
 * selfie gallery.
 * TODO: SET AND CHECK A COOKIE FOR RATE LIMITING!
 */
$app->post('/media/selfie/', function() {
   $res = new APIResponse(['public']);
   // Remember to set these setting vaules
   $galleryId = SettingsLib::get('selfie-gallery-id');
   $selfieUser = SettingsLib::get('selfie-user-id');
   // Get a media manager to upload files, and the gallery to add them to
   $media = new MediaManager($selfieUser);
   $gallery = new Gallery($galleryId);

   // Now, upload the files
   if ($uploaded = $media->upload($_FILES)) {
      $res->addData(['uploaded' => $uploaded]);
      // Use the IP address of the user as the caption. 
      $caption = $_SERVER['REMOTE_ADDR'];
      foreach ($uploaded as $media) {
         $gallery->addMedia($media['medid'], $caption);
      }
      $res->addData(['caption' => $caption]);
   } else {
      $res->error("There was a problem while uploading the selfie.");
   }

   $res->respond();

});
