<?php

class SettingsLib {

   private static $cache = [];

   public static function get($name) {
      if(isset(self::$cache[$name])) {
         return self::$cache[$name];
      } else {
         $q = "SELECT `value` FROM settings WHERE `name` = %s";
         $val = DB::queryFirstField($q, $name);
         // If $val is empty, log that it has been queried for but
         // is unset.
         if (empty($val)) {
            Utils::logMe("Setting $name queried but unset.");
         }
         self::$cache[$name] = $val;   
         return $val;
      }
   }
   public static function all() {
      $q = "SELECT * FROM settings";
      return DB::query($q);
   }

   public static function set($name, $value) {
      $del = "DELETE FROM settings WHERE `name` = %s";
      $ins = "INSERT INTO settings SET `name` = %s, `value` = %s";
      DB::query($del, $name);
      DB::query($ins, $name, $value);
      self::$cache[$name] = $value;
   }

}
