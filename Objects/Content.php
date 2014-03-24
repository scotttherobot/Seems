<?php

class Content {

   public $id = false;
   public $row = [];
   public $author;
   public $type = 'POST';
   public $title;
   public $leader = NULL;
   public $body;
   public $date;
   public $published = 0;
      
   public $userid;

   public static function all() {
      return DB::query('
         SELECT c.id, c.date, c.type, c.published,
          c.title, c.body, m.src as leader, u.username
         FROM content c
         LEFT JOIN media m
         ON m.medid = c.leader
         JOIN users u
         ON u.userid = c.userid
         WHERE published = 1');
   }

   public function __construct($user, $id = false) {
      $this->userid = $user;
      if ($id) {
         // If we know that the post id is, read it from the database.
         $this->id = $id;
         $this->read();
      } else {
         // If we don't, leave the id as false. 
         // We will create it when we save.
      }
   }

   public function update($attr, $value) {
      $this->$attr = $value;
   }

   public function save() {
      // We're creating a new post
      if (!$this->id) {
         DB::insert('content', [
            'userid' => $this->userid,
            'date' => time(),
            'type' => $this->type,
            'published' => $this->published,
            'title' => $this->title,
            'body' => $this->body,
            'leader' => $this->leader,
         ]);
         $this->id = DB::insertId();
      } else {
      // We;re updating an existing post.
         DB::update('content', [
            'date' => time(),
            'type' => $this->type,
            'published' => $this->published,
            'title' => $this->title,
            'body' => $this->body,
            'leader' => $this->leader,
         ], "id = %i", $this->id);
      }
   }

   public function read() {
      $post = DB::queryFirstRow('
         SELECT *
         FROM content
         WHERE id = %i', $this->id);

      $this->row = $post ?: [];
      $this->set($post);
   }

   public function set($post) {
      if ($post) {
         // If we have the data, update it, else leave it as
         // the previous value
         $this->id = idx($post, 'id', $this->id);
         $this->author = idx($post, 'author', $this->author);
         $this->type = idx($post, 'type', $this->type);
         $this->date = idx($post, 'date', $this->date);
         $this->body = idx($post, 'body', $this->body);
         $this->title = idx($post, 'title', $this->title);
         $this->leader = idx($post, 'leader', $this->leader);
         $this->published = idx($post, 'published', $this->published);
      }
   }

}
