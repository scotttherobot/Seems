<?php

/**
 * Utilities for doing push notification shenanigans.
 */

class PushLib {

   public static function subscribe($userid, $type, $uuid) {
      DB::insert('subscriptions', [
         'userid' => $userid,
         'type' => $type,
         'uuid' => $uuid,
      ]);
      return DB::insertId();
   }

   public static function setNotifications($userid, $uuid, $on) {
      DB::update('subscriptions', [
         'notifications' => $on,
      ], "WHERE userid = %i AND uuid = %s", $userid, $uuid);
   }

   public static function sendNotification($apiKey, $registrationIdsArray, $messageData) {

      $headers = [
         "Content-Type:" . "application/json", 
         "Authorization:" . "key=" . $apiKey
      ];

      $data = [
         'data' => $messageData,
         'registration_ids' => $registrationIdsArray
      ];

      $ch = curl_init();

      curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers ); 
      // Set request method to POST
      curl_setopt( $ch, CURLOPT_POST, true );
      curl_setopt( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
      curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
      curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

      $response = curl_exec($ch);
      curl_close($ch);

      return $response;
   }

}
