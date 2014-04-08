<?php

$app->get('/media/galleries/', function () {
   $res = new APIResponse(['user']);
   $galleries = Gallery::all();

   $res->addData(['galleries' => $galleries]);

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
