<?php

class UserLocation {

   public $userid;
   public $latitude;
   public $longitude;

   function __construct($userid) {
      $this->userid = $userid;
   }

   public function updateLocation($lat, $lon) {
      $this->latitude = $lat;
      $this->longitude = $lon;
   }

   public function save() {
      DB::insert('user_locations', [
         'userid' => $this->userid,
         'latitude' => $this->latitude,
         'longitude' => $this->longitude,
         'date' => time(),
      ]);
      return DB::insertId();
   }

   public function locationHistory($limit = 10, $offset = 0) {
      return DB::query("
         SELECT latitude, longitude, date
         FROM user_locations
         WHERE userid = %i
         ORDER BY date DESC
         LIMIT %i
         OFFSET %i", $this->userid, $limit, $offset);
   }

   /**
    * Get the closest OTHER users.
    * One record (the most recent) per user.
    */
   public function nearbyUsers($limit = 25) {
      $nearby = DB::query("
         SELECT s.userid, s.latitude, s.longitude,
         s.date, u.firstname, u.lastname, u.username,
         u.email, p.about, p.avatar, m.src,
         ROUND ( ( 3959 * acos( cos( radians( %d ) ) *
            cos( radians( s.`latitude` ) ) *
            cos( radians( s.`longitude` ) -
            radians( %d ) ) +
            sin( radians( %d ) ) *
            sin( radians( s.`latitude` ) ) ) ), 5)
         AS distance
         FROM user_locations s
         JOIN users u USING (`userid`)
         LEFT JOIN profiles p USING (`userid`)
         LEFT JOIN media m ON (p.avatar = m.medid)
         WHERE s.date = (
           SELECT max(date)
           FROM user_locations ul
           WHERE ul.userid = s.userid
         )
         ORDER BY distance ASC
         LIMIT %i", 
         $this->latitude, $this->longitude, $this->latitude, $limit);
      return $nearby;
   }

   public function inRadius($radius = 1) {
   }

}
