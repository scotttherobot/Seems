<?php

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as LocalAdapter;

class MediaManager {

   private static $uploadDir = "media";
   private $adapter;
   private $filesystem;
   public $userid;

   function __construct($userid) {
      $this->userid = $userid;
      $this->adapter = new LocalAdapter(static::$uploadDir);
      $this->filesystem = new Filesystem($this->adapter);
   }

   public function media() {
      return DB::query("
         SELECT medid, date, type, fname, src
         FROM media
         WHERE userid = %i", $this->userid);
   }

   /**
    * Generates a url path.
    */
   public function src($name) {
      if ($name) {
         return "/". self::$uploadDir ."/".$name;
      }
      else
         return false;
   }

   /**
    * Get the metadata on a file.
    */
   public function meta($medid) {
      return DB::queryFirstRow("
         SELECT *
         FROM media
         WHERE medid = %i", $medid);
   }

   public function upload($files) {
      /**
       * Upload procedure:
       * 1) Upload the image.
       * 2) IFF it transferred successfully, create an
       *    entry for it in the media table.
       * 3) return data about it?
       */
      $uploaded = [];
      foreach ($files as $f) {
         $uuid = uniqid();
         $tmpPath = $f['tmp_name'];
         $cleanName = preg_replace('/\s+/', '', $f['name']);
         $name = $this->userid."_${uuid}_".$cleanName;

         try {
            $fileObj = new File($name, $this->filesystem);
            $fileObj->setContent(file_get_contents($tmpPath));
            $uploaded[] = $this->newMediaEntry($name, 'IMAGE');
         }
         catch (Exception $e) {
            Utils::logMe("Exception!! OH NO!");
         }
      }
      return $uploaded;
   }

   public function remove($id) {
   }

   private function newMediaEntry($name, $type) {
      $date = time();
      DB::insert('media', [
         'userid' => $this->userid,
         'date' => $date,
         'type' => $type,
         'fname' => $name,
         'src' => $this->src($name),
      ]);
      $medid = DB::insertId();
      return $medid ? [
         'medid' => $medid,
         'date' => $date,
         'type' => $type,
         'fname' => $name,
         'src' => $this->src($name),
      ] : false;
   }

}
