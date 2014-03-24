<?php

// A class to do the dirty work of creating password hashes
// and verifying hashes against their plaintext equivalents.

class CryptLib {

   // Check a plaintext password against the hash
   // in the database.
   public static function checkHash($hash, $password) {
      return crypt($password, $hash) == $hash;
   }

   // Create a hash
   public static function createHash($password) {
      $cost = 10;
      $salt = uniqid(mt_rand(), true);
      $salt = sprintf("$2a$%02d$", $cost) . $salt;
      $hash = crypt($password, $salt);
      return $hash;
   }


}
