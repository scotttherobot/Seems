<?php

class MediaLib {
   
   public static function newImage($opt) {
      DB::insert('media', [
         'userid' => idx($opt, 'userid'),
         'type' => 'IMAGE',
         'fname' => idx($opt, 'fname'),
         'src' => idx($opt, 'src'),
         'date' => idx($opt, 'date', strtotime('now'))
      ]);
      return DB::insertId();
   }

   public static function getImage($id) {
      return DB::queryFirstRow("
         SELECT * FROM media
         WHERE `medid` = %i", $id);
   }

   public static function getUserImages($id) {
      return DB::query("
         SELECT * FROM media
         WHERE `userid` = %i", $id);
   }
}
