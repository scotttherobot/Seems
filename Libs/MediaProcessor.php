<?php

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as LocalAdapter;
use Intervention\Image\Image;

class MediaProcessor {

   public static $sizes = [
      // Friendly, human name
      'medium' => [
         // The column prefix in the database
         'col' => 'medium',
         // The long and short sides
         // (to allow portrait images)
         'long' => 800,
         'short' => 600,
      ],
      'small' => [
         'col' => 'small',
         'long' => 320,
         'short' => 240,
      ],
   ];

   public static function generateMediaVersions($medid) {
      $response = [
         'status' => 'failure',
         'message' => '',
      ];
      $media_q = <<<EOT
SELECT *
FROM media
WHERE medid = %i
EOT;
      $row = DB::queryFirstRow($media_q, $medid);
      if (!$row) {
         $response['message'] = "No such image $medid";
         return $response;
      }
      // Now we do the processing
      else {
         $created = [];
         $original = $row['fname'];
         // Get the image from the FILESYSTEM ABSTRACTION LAYER
         // AND NOT THE FILESYSTEM DIRECTLY
         $fs = MediaManager::getFilesystem();
         $rawImage = $fs->read($original);
         $img = Image::make($rawImage);
         // Enable interlacing (progressive mode)
         $img->interlace();
         // Determine if it's portrait or landscape
         $portrait = $img->width / $img->height < 1 ? true : false;
         
         $createAndSave = function($size) use ($img, $portrait, &$created, $original, $fs, $medid) {
            $is = $portrait ? 'is' : 'is not';
            $width = $portrait ? $size['short'] : $size['long'];
            $height = $portrait ? $size['long'] : $size['short'];
            // "smart crop" it down to the size it needs to be
            $img->grab($width, $height);
            // Now, get the raw and save it using Gaufrette.
            $newRaw = $img->encode();
            $newName = "$width-$height-$original";
            // Write it, overwriting it if it's already there. #YOLO
            $fs->write($newName, $newRaw, true);
            self::updateSizeRecord($medid, $size, $newName);
            $created[] = $newName;
         };
         array_map($createAndSave, self::$sizes);
         $response['status'] = 'success';
         $response['created'] = $created;
         return $response;
      }
   }

   public static function updateSizeRecord($medid, $size, $fname) {
      $column = $size['col'];
      $src = "/media/$fname";
      $q_version = <<<EOT
INSERT INTO media_sizes
SET medid = %i,
${column}_fname = %s,
${column}_src = %s
ON DUPLICATE KEY UPDATE
${column}_fname = %s,
${column}_src = %s
EOT;
      DB::query($q_version, $medid, $fname, $src, $fname, $src);

   }
}
