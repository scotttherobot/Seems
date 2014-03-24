<?php

class ProfileLib {
   
   public static function getProfile($userid) {
      $q = <<<EOT
         SELECT *
         FROM profiles
         WHERE `userid` = %i
EOT;
      return DB::queryFirstRow($q);
   }

   public static function update($userid, $data) {
      DB::insertUpdate('profiles', [
         'userid' => $data['userid'],
         'realName' => $data['realName'],
      ]);
   }

}
