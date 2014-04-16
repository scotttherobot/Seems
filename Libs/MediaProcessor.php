<?php

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as LocalAdapter;
use Intervention\Image\Image;

class MediaProcessor {

   public static $sizes = [
      'medium' => [
         'long' => 800,
         'short' => 600,
      ],
      'thumb' => [
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
         
         $createAndSave = function($size) use ($img, $portrait, &$created, $original, $fs) {
            $is = $portrait ? 'is' : 'is not';
            $width = $portrait ? $size['short'] : $size['long'];
            $height = $portrait ? $size['long'] : $size['short'];
            print("image $is portrait, $width wide x $height high\n");
            // "smart crop" it down to the size it needs to be
            $img->grab($width, $height);
            // Now, get the raw and save it using Gaufrette.
            $newRaw = $img->encode();
            $newName = "$width-$height-$original";
            // Write it, overwriting it if it's already there. #YOLO
            $fs->write($newName, $newRaw, true);
            $created[] = $newName;
         };
         array_map($createAndSave, self::$sizes);
         //$img->resize(320, 240);
         //$img->save(static::$uploadDir . "/320_240_$original");
         $response['status'] = 'success';
         $response['created'] = $created;
         return $response;
      }
   }

   public static function updateSizeRecord($medid, $size, $fname, $src) {

   }
}
