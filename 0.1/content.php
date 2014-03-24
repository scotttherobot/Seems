<?php

$app->get("/content/:id/", function ($id) {
   $res = new APIResponse(['user']);

   $content = new Content($res->userid, $id);

   $res->addData($content->row);

   $res->respond();
});

$app->post("/content/:id/", function ($id) {
   $res = new APIResponse(['user']);
   $content = new Content($res->userid, $id);

   foreach ($_POST as $attr => $val) {
      $content->update($attr, $val);
   }

   $content->save();

   $res->respond();
});

$app->post("/content/", function () {
   $res = new APIResponse(['user']);
   $content = new Content($res->userid);

   foreach ($_POST as $attr => $val) {
      $content->update($attr, $val);
   }

   $content->save();

   $res->addData(['id' => $content->id]);

   $res->respond();
});

$app->get("/content/", function () {
   $res = new APIResponse(['user']);

   $res->addData([
      'content' => Content::all(),
   ]);

   $res->respond();
});
