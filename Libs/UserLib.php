<?php

// This is a user management library
// like, for managing users and stuff

class UserLib {

   public static function getAll($type = 0){
      $results = DB::query("
         SELECT *
         FROM users");
      return $results;
   }

   public static function makeAdmin($uid) {
      $results = DB::query("
         UPDATE users
         SET group_id = 2
         WHERE user_id = %i", $uid);
      return $results;
   }

   public static function isAdmin($uid) {
      $results = DB::queryFirstField("
         SELECT group_id
         FROM users
         WHERE user_id = %i", $uid);
      return $results == 2;
   }

   public static function makeStandard($uid) {
      $results = DB::query("
         UPDATE users
         SET group_id = 1
         WHERE user_id = %i", $uid);
      return $results;
   }

   public static function disable($uid) {
      $results = DB::query("
         UPDATE users
         SET activated = 0
         WHERE user_id = %i", $uid);
      return $results;
   }

   public static function enable($uid) {
      $results = DB::query("
         UPDATE users
         SET activated = 1
         WHERE user_id = %i", $uid);
      return $results;
   }

}

?>
