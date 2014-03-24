<?php

class Utils {

   public static function logMe($string) {
      try {
         $file = fopen('Logs/log.txt', 'a');
         fwrite($file, date("H:i:s"). ": " . $string . "\n");
         fclose($file);
      } catch (Exception $e) {
         echo "Caught exception " . $e->getMessage . "\n";
      }
   }

   public static function randomWord() {
      $words = [
         'unascended', 'crusty',
         'crustal', 'fishy',
         'importless', 'terrible',
         'pantophobia', 'ratchet',
         'monobloc', 'clever',
         'lateromarginal', 'lotion',
         'marvelment', 'music',
         'predeliberate', 'envelope',
         'handshake', 'lipstick',
         'pathonomia', 'glass',
         'springhaas', 'feline',
         'overgreediness', 'hood',
         'doxastic', 'queen',
         'implex', 'taco',
         'boobyism', 'foot',
         'crayon', 'destroyed',
         'banana', 'republic',
         'foster', 'people',
         'creative', 'words',
         'teach', 'gym',
      ];
      $index = array_rand($words, 10);
      $other = array_rand($words, 10);

      $key1 = mt_rand(0, 9);
      $key2 = mt_rand(0, 9);

      $lead = mt_rand(0, 100);
      $tail = mt_rand(0, 100);
      return "$lead-".$words[$index[$key1]]."-".$words[$other[$key2]]."-$tail";
   }
}

function idx(array $array, $key, $default = null) {
   if (isset($array[$key])) {
      return $array[$key];
   }

   // Comparing $default is also a micro-optimization.
   if ($default === null || array_key_exists($key, $array)) {
      return null;
   }

   return $default;
}
