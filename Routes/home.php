<?php

$app->get(URI::tag('HOME'), function() use ($app) {
   $page = new Page($app);

   $page->addTemplate('home.phtml');
   $page->setTitle("Home");

   $page->render();
});

$app->get(URI::tag('LOGIN'), function() use ($app) {
   $page = new Page($app);

   $page->addTemplate('login.phtml');
   $page->setTitle("Login");

   $page->render();
});

$app->post(URI::tag('LOGIN'), function () use ($app) {
   $username = idx($_POST, 'username', '');
   $password = idx($_POST, 'password', '');

   $user = User::login($username, $password);

   if (!$user) {
      $page = new Page($app);
      $page->addTemplate('login.phtml');
      $page->setTitle("Login");
      $page->error("There was a problem logging in. Please try again.");
      $page->render();
   } else {
      $app->redirect('/');
   }
});
