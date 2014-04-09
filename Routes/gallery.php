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

   $page->addRemoteScript('/3P/reveal/jquery.reveal.js');
   $page->addRemoteStyle('/3P/reveal/reveal.css');
   $page->addTemplateSet("mediaManager");

   $gallery = new Gallery($id);

   $page->addJSData(['gallery' => $gallery->getRow()]);

   $page->addData([
      'gallery' => $gallery,
      'previousPage' => false,
      'nextPage' => false
   ]);

   $page->render();
});
