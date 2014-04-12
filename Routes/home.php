<?php

$app->get(URI::tag('HOME'), function() use ($app) {
   $page = new Page($app);

   $page->addTemplate('home.phtml');
   $page->addStyle('home.css');
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

$app->get(URI::tag('SIGNUP'), function() use ($app) {
   $per = SettingsLib::get('public-registration') ? ['public'] : ['user'];
   $page = new Page($app, $per);
   $page->addTemplateSet("signup");
   $page->enableNav();
   $page->render();
});
