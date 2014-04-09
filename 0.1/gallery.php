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
