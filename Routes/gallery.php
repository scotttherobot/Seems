<?php

$app->get('/galleries/', function () use ($app) {
   $page = new Page($app, ['public']);
   $page->addTemplateSet("gallery");
   $page->enableNav();

   $galleries = Gallery::all();

   $page->addData([
      'galleries' => $galleries,
      'previousPage' => false,
      'nextPage' => false
   ]);

   $page->render();
});

$app->get('/galleries/:id', function ($id) use ($app) {
   $page = new Page($app, ['public']);
   $page->addTemplateSet("galleryPage");
   $page->enableNav();

   $gallery = new Gallery($id);

   $page->addData([
      'gallery' => $gallery,
      'previousPage' => false,
      'nextPage' => false
   ]);

   $page->render();
});
