<?php

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as LocalAdapter;
use Intervention\Image\Image;

class MediaManager {

   private static $uploadDir = "media";
   // Turn version generation on or off.
   private static $generateVersions = true;
   private $adapter;
   private $filesystem;
   public $userid;

   public static function URI($medid) {
      $src = DB::queryFirstField('
         SELECT src
         FROM media
         WHERE medid = %i', $medid);
      // TODO: return alt image if there isn't one
      return $src;
   }

   public static function queueVersionGeneration(array $medids = []) {
      $beanstalk = new Socket_Beanstalk();
      $beanstalk->connect();
      $beanstalk->choose('media');
      foreach ($medids as $medid) {
         $job = [
            'medid' => $medid,
         ];
         $beanstalk->put(23, 0, 500, json_encode($job));
      }
      $beanstalk->disconnect();
   }

   function __construct($userid) {
      $this->userid = $userid;
      $this->adapter = self::getAdapter();
      $this->filesystem = self::getFilesystem($this->adapter);
   }

   // Return the Gaufrette filesystem. If $adapter is false,
   // fetch the default one.
   public static function getFilesystem($adapter = false) {
      if (!$adapter)
         $adapter = self::getAdapter();
      return new Filesystem($adapter);
   }
   // Return the Gaufrette filesystem adapter.
   // If $dir is false, use the default.
   public static function getAdapter($dir = false) {
      if (!$dir)
         $dir = static::$uploadDir;
      return new LocalAdapter($dir);
   }

   public function media() {
      return DB::query("
         SELECT m.medid, m.date, m.type, m.fname, m.src,
          ms.small_src, ms.medium_src
         FROM media m
         LEFT JOIN media_sizes ms
         ON m.medid = ms.medid
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
            $upl = $this->newMediaEntry($name, 'IMAGE');
            if (self::$generateVersions)
               self::queueVersionGeneration([$upl['medid']]);
            $uploaded[] = $upl;
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
