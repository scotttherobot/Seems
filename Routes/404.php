<?php

$app->notFound(function() use ($app) {
   $page = new Page($app);

   $page->addTemplate('404.phtml');
   $page->addStyle('404.css');
   $page->setTitle("404");

   $page->enableNav();

   $page->render();
});
