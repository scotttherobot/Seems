<?php

class URI {

   private static $tags = [
      'NAV' => [
         'HOME' => '/',
         'LOGIN' => '/login/',
         'LOGOUT' => '/logout/',
         'SIGNUP' => '/signup/',
         'ABOUT' => '/about/',
         'BLOG' => '/blog/',
         'EDIT' => '/edit/',
         'CREATE' => '/create/',
      ],
   ];

   public static function navLinks() {
      return self::$tags['NAV'];
   }

   public static function tag($tag) {
      return idx(self::$tags['NAV'], $tag, NULL);
   }

   public static function isActive($tag, $bool = false) {
      $return = $bool ?: "active";
      $route = idx(self::$tags['NAV'], $tag, NULL);
      $request = idx($_SERVER, 'REQUEST_URI', NULL);
      $path = explode("/", trim($request));
      $path = "/$path[1]/";
      return $path == $route ? $return : false;
   }
   
   public static function content($id, $name = "") {
      $name = preg_replace('/\s+/', '+', $name);
      return "/p/$id/$name";
   }

}
