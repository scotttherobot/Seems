<?php

/**
 * A thread object.
 * Allows for conversation/IM threads between any number of users.
 */

class Thread {

   public $threadid = false;
   public $userid = false;

   /**
    * Create a new thread in the DB and auto-joins the creator.
    * The userid is the user on behalf of whom to act.
    */
   public static function newThread($name, $userid) {
      DB::insert('threads', [
         'name' => $name,
      ]);
      $id = DB::insertId();
      if ($id) {
         $thread = new Thread($id, $userid);
         $thread->addUser($userid);
         return $thread;
      } else {
         return false;
      }
   }

   /**
    * Returns all threads that a user is participating in.
    */
   public static function myThreads($userid) {
      return DB::query("
         SELECT p.*, t.name
         FROM participants p
         JOIN threads t ON (p.`threadid` = t.`id`)
         WHERE userid = %i
         AND status != 'LEFT'", $userid);
   }

   /**
    * Returns a single element of the query above
    */
   public static function myThread($userid, $threadid) {
      return DB::queryFirstRow("
         SELECT p.*, t.name
         FROM participants p
         JOIN threads t ON (p.`threadid` = t.`id`)
         WHERE userid = %i
         AND threadid = %i
         AND status != 'LEFT'", $userid, $threadid);
   }

   /**
    * Instantiate a thread object.
    * The userid is the user on behalf of whom to act.
    */
   public function __construct($threadid, $userid) {
      $this->threadid = $threadid;
      $this->userid = $userid;
   }

   /**
    * Returns the thread metadata.
    */
   public function meta() {
      return DB::queryFirstRow("
         SELECT t.id, t.name, (
            SELECT COUNT(*)
            FROM participants p
            WHERE p.threadid = t.id
            AND p.status != 'LEFT'
         ) as userCount, (
            SELECT COUNT(*)
            FROM messages m
            WHERE m.threadid = t.id
         ) as messageCount, (
            SELECT username
            FROM participants pa
            JOIN users u USING (`userid`)
            WHERE pa.threadid = t.id
            ORDER BY joined ASC
            LIMIT 1
         ) as createdBy
         FROM threads t
         JOIN participants par ON (t.`id` = par.`threadid`)
         JOIN users user ON (user.`userid` = par.`userid`)
         WHERE par.status != 'LEFT'
         AND user.`userid` = %i
         AND t.id = %i", $this->userid, $this->threadid);

   }

   /**
    * Fetch the entire historical transcript for this thread.
    * Useful for when new users join a thread.
    */
   public function transcript() {
      return $this->newSince(0);
   }

   /**
    * Fetch new messages since a particular unixtime.
    * Useful for updating the device's version of the
    * thread.
    */
   public function newSince($time) {
      return DB::query("
         SELECT m.id, m.body, m.sent, m.medid, u.username, u.userid, p.src, ava.src as avatar
         FROM messages m
         JOIN users u USING (`userid`)
         LEFT JOIN media p ON (m.medid = p.medid)
         LEFT JOIN profiles pro ON (u.userid = pro.userid)
         LEFT JOIN media ava ON(pro.avatar = ava.medid)
         WHERE threadid = %i
         AND sent > %i
         ORDER BY sent ASC", $this->threadid, $time);
   }

   /**
    * Sends a message in the thread.
    */
   public function sendMessage($message) {
      DB::insert('messages', [
         'threadid' => $this->threadid,
         'userid' => $this->userid,
         'body' => idx($message, 'body', NULL),
         'medid' => idx($message, 'medid', NULL),
         'sent' => time(),
      ]);
      $this->notify("MESSAGE_SENT", idx($message, 'body', 'New Message'));
      return DB::insertId();
   }

   /**
    * Invites/joins a user to a thread.
    */
   public function addUser($userid = false) {
      $userid = $userid ?: $this->userid;
      // TODO: Check to see if the user is already in the thread!!
      DB::insert('participants', [
         'threadid' => $this->threadid,
         'userid' => $userid,
         'joined' => time(),
      ]);
      $this->notify("USER_JOINED", "added you to a thread");
      return DB::insertId();
   }

   /**
    * Removes a user from the thread.
    */
   public function leave($userid = false) {
      $userid = $userid ?: $this->userid;
      DB::update('participants', [
         'left' => time(),
         'status' => 'LEFT',
      ], 'userid = %i AND threadid = %i', $userid, $this->threadid);
   }
   
   /**
    * Renames a thread
    */
   public function rename($name) {
      DB::update('threads', [
         'name' => $name,
      ], 'id = %i', $this->threadid);
   }

   /**
    * Returns the users that have participated in the thread,
    * including their status.
    */
   public function participants() {
      return DB::query("
         SELECT userid, status, notifications, joined, `left`
         FROM participants
         WHERE threadid = %i", $this->threadid);
   }

   /**
    * sets notification status for for a user.
    */
   public function notifications($userid, $on) {
      $on = $on ? 'ON' : 'OFF';
      DB::update('participants', [
         'notifications' => $on,
      ], "userid = %i", $userid);
   }

   /**
    * sends notification of new message to participants that have
    * push notifications turned on.
    * OVERRIDE AND IMPLEMENT THIS IN CLASS EXTENSION???
    */
   public function notify($action, $message = 'New Activity') {
      $user = User::byId($this->userid);

      $data = [
         'event' => $action,
         'threadid' => $this->threadid,
         'userid' => $this->userid,
         'title' => $user->username,
         'message' => $message,
      ];

      $beanstalk = new Socket_Beanstalk();
      $beanstalk->connect();
      $beanstalk->put(23,0,500,json_encode($data));
      $beanstalk->disconnect();
   }
}
