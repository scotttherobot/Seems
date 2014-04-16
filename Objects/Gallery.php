<?php

class Gallery {

   public $id = false;
   public $row = false;
   public $entries = false;

   public static function create($userid, $name) {
      // Create a new entry in the `galleries` table and fetch the id.
      // Then, return an instantiated gallery object.
      $id = self::createGallery($userid, $name);
      $gal = new Gallery($id);
      return $gal;
   }

   public static function all($offset = 0, $limit = 10) {
      return DB::query("
         SELECT g.id, g.title, g.userid, g.leader, m.src
         FROM galleries g
         LEFT JOIN media m
         ON g.leader = m.medid
         ORDER BY id DESC
         LIMIT %i
         OFFSET %i", $limit, $offset);

   }

   function __construct($id) {
      $this->id = $id;
      $this->getRow();
      $this->getEntries();
   }

   public function getRow() {
      if ($this->row)
         return $this->row;
      $this->row = DB::queryFirstRow("
         SELECT g.*, m.src
         FROM galleries g
         LEFT JOIN media m
         ON g.leader = m.medid
         WHERE id = %i
         AND published = 1", $this->id);
      return $this->row;
   }

   public function getEntries() {
      if ($this->entries)
         return $this->entries;
      $this->entries = DB::query("
         SELECT g.medid, g.caption, m.fname, m.src
         FROM gallery_entries g
         LEFT JOIN media m
         ON g.medid = m.medid
         WHERE gallery_id = %i", $this->id);
      return $this->entries;
   }

   public function addMedia($medid, $caption = '') {
      DB::insert('gallery_entries', [
         'gallery_id' => $this->id,
         'medid' => $medid,
         'caption' => $caption
      ]);
      // Invalidate the current set of entries, we just added one.
      $this->entries = false;
   }

   public static function updateCaption($id, $caption) {
      DB::update('gallery_entries', [
         'caption' => $caption
      ], 'id = %i', $id);
   }

   public static function createGallery($userid, $title) {
      DB::insert('galleries', [
         'title' => $title,
         'user' => $userid,
      ]);
      return DB::insertId();
   }

}
