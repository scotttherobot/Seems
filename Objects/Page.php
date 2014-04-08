<?php

/**
 * A page object.
 */

class Page {

   public static $app = false;

   public $user = false;
   public $userid;
   public $titlePrefix = 'Scott Vanderlind';
   public $title = false;
   public $style = [
      '/Templates/scott/CSS/global.css',
   ];
   public $scripts = [];
   public $theme = 'scott';
   public $data = [];
   public $jsdata = [];

   // The templates to render by default.
   public $templates = [
      'header' => ['header.phtml'],
      'body' => [],
      'footer' => [],
   ];

   // Permission levels
   private $permissions = [
      'user' => [
         'User','auth',
      ],
   ];

   public function __construct($app, $allowed = ['public'], $user = false) {
      static::$app = $app;

      // If a user was passed in, let's use that for the context of the page.
      // Otherwise, get the singleton user.
      $this->user = $user ?: User::auth();
      if ($this->user)
         $this->userid = $this->user->userid;

      // Now check page permission level.
      $auth = false;
      $auth = $auth || in_array('public', $allowed);
      if (!$auth && $this->user) {
         foreach ($allowed as $type) {
            $auth = $auth ||
               forward_static_call_array($this->permissions[$type], [$this->user->userid]);
         }
      }
      if (!$auth) {
         self::$app->notFound();
         /*
         $groups = implode(" or ", $allowed);
         $this->error("Unauthorized. You must be a $groups to use this page.", true);
          */
      }

   }

   public function enableNav() {
      $this->templates['header'][] = 'navigation.phtml';
      $this->addScript('navigation.js');
      $this->addStyle('navigation.css');
   }

   public function addTemplate($template) {
      $this->templates['body'][] = $template;
   }

   public function addData($data) {
      $this->data = array_merge($this->data, $data);
   }

   public function addJSData($data) {
      $this->jsdata = array_merge($this->jsdata, $data);
   }

   public function setTitle($title) {
      $this->title = $title;
   }

   public function setTheme($theme) {
      $this->theme = $theme;
   }

   public function addStyle($stylesheet) {
      $this->style[] = "/Templates/$this->theme/CSS/$stylesheet";
   }

   public function addRemoteStyle($script) {
      $this->style[] = $script;
   }

   public function addScript($script) {
      $this->scripts[] = "/Templates/$this->theme/JS/$script";
   }

   public function addRemoteScript($script) {
      $this->scripts[] = $script;
   }

   public function addTemplateSet($prefix) {
      $this->addTemplate("$prefix.phtml");
      $this->addStyle("$prefix.css");
      $this->addScript("$prefix.js");
   }

   public function render($return = false) {
      // Set the page title
      $title = $this->titlePrefix . (!$this->title ? "" : " - $this->title");
      static::$app->view->setData('title', $title);
      // Collect the items that go in the nav.
      static::$app->view->setData('navLinks', URI::navLinks());
      // And the user
      static::$app->view->setData('user', $this->user);
      // Styles and Scripts
      static::$app->view->setData('styles', $this->style);
      static::$app->view->setData('scripts', $this->scripts);
      // JS App data
      static::$app->view->setData('jsdata', json_encode($this->jsdata));
      // Add the rest of the data.
      static::$app->view->appendData($this->data);

      // And now the templates.
      foreach($this->templates as $section) {
         foreach($section as $template) {
            static::$app->render("$this->theme/$template");
         }
      }
      die();
   }

   public function error($message, $fatal = false) {
      $this->templates['header'][] = 'error.phtml';
      static::$app->view->setData('errorMessage', $message);

      if ($fatal)
         $this->render();
   }

   public function addAuthenticator($callable, $redirect = false) {
      if (is_callable($callable)) {
         if ($callable()) {
            return true;
         }
      }
      // Probably should die here.
      static::$app->redirect('/403/');
      return false;
   }

}
