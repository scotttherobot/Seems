<?php

/**
 * This class intents to manage API sessions.
 * It should be the only class that touches the sessions
 * table. It provides functions to do forward and reverse
 * lookups by sessionid as well as by userid.
 *
 * By Scott Vanderlind
 * August 2, 2013
 */
class SessionManager {

   private static $sessionLength = "+2 days";

   /**
    * A method to return the current session for 
    * a particular userid.
    *
    * Returns the session if valid, or false otherwise.
    */
   public static function getSessionFor($userid) {
      // Check to see if that userid has a session.
      // If so, make sure it's not expired.
      // If not, create one.
      $q_getUser = "
         SELECT *
         FROM sessions
         WHERE `userid` = %i";
      // First check that there's a result.
      $row = DB::queryFirstRow($q_getUser, $userid);
      if($row) {
         $key = $row['api_key'];
         $expire = $row['expire'];
      }

      return ($row && self::isValid($expire)) ? $key : false;
   }

   public static function getUser($userid) {
      $q_getUser = "
         SELECT `userid`, `username`, `sign_up_date`
         FROM users
         WHERE `userid` = %i";
      // First check that there's a result.
      return DB::queryFirstRow($q_getUser, $userid);
   }

   /**
    * A method to create a new session for a particular user.
    * Generates a session key, inserts it into the database,
    * and returns the key.
    */
   public static function createSessionFor($userid) {
         // create a new session. Insert it.
         $key = self::generateKey($userid);
         $expire = self::expireTime();
         $q_updateKey = "
            REPLACE INTO sessions
            SET `api_key` = %s,
            `expire` = %i,
            `userid` = %i";
         DB::query($q_updateKey, $key, $expire, $userid);
         return $key;
   }

   /**
    * A method to delete an existing session for a particular user.
    */
   public static function deleteSessionFor($userid) {
      $q_deleteKey = "
         DELETE FROM sessions
         WHERE `userid` = %i";
      DB::query($q_deleteKey, $userid);
   }

   /**
    * A method to delete an existing session by key.
    */
   public static function deleteSession($key) {
      $q_deleteKey = "
         DELETE FROM sessions
         WHERE `api_key` = %s";
      DB::query($q_deleteKey, $key);
   }

   /**
    * Does a reverse lookup of userid using the API key.
    * Pass it the API key, and it'll return the userid
    * if the key is still valid, or false if not.
    */
   public static function getUseridFromKey($key) {
      $q_getUser = "
         SELECT `userid`, `expire`
         FROM sessions
         WHERE `api_key` = %s";
      $row = DB::queryFirstRow($q_getUser, $key);
      return ($row && self::isValid($row['expire'])) ? $row['userid'] : false;
   }

   /**
    * Determines if an expire time has elapsed.
    */
   private static function isValid($expire) {
      return $expire > strtotime("now");
   }

   /**
    * Generates a key based on the user's ID.
    */
   private static function generateKey($userid) {
      return md5($userid . time());
   }

   /**
    * Generates a session expire time based on the 
    * current time and adding the declared session length.
    */
   private static function expireTime() {
      return strtotime(self::$sessionLength);
   }  
}
