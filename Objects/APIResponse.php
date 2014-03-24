<?php

class APIResponse {
      
   private $response = [
      'status' => 'success',
      'errors' => [],
      'userid' => '',
   ];

   private $permissions = [
      'user' => [
         'User','auth',
      ],
   ];

   public $user = null;
   public $userid = null;

   public function __construct($allowed = ['public']) {
      $auth = false;
      header("Content-Type: application/json");
      // TODO: better handle 'public'
      // User::auth() might return an anon user object?
      $user = User::auth();
      $auth = false;
      $auth = $auth || in_array('public', $allowed);
      if(!$auth && $user) {
         $this->user = $user;
         $this->userid = $user->userid;
         foreach ($allowed as $type) {
            $auth = $auth || 
               forward_static_call_array($this->permissions[$type], [$user->userid]);
         }
      }
      if (!$auth) {
         $groups = implode(" or ", $allowed);
         $this->error("Unauthorized. You must be a $groups to use this endpoint.");
         $this->respond();
      }
   }

   /**
    * Iterates over the list of params and verifies they are there.
    * If any are missing from the array, we error out.
    */
   public function params($array, $params, $respondImmediately = true) {
      $errors = false;
      $pulled = [];
      foreach ($params as $param) {
         $val = idx($array, $param, NULL);
         if (is_null($val) || empty(trim($val))) {
            $errors[] = "Missing parameter $param.";
         } else {
            $pulled[$param] = $val;
         }
      }
      if ($errors) {
         foreach ($errors as $e) {
            $this->error($e, false);
         }
         if ($respondImmediately) {
            $this->respond();
         }
      }
      return $pulled;
   }

   /**
    * Adds the value at the specified place, and errors if
    * the value is false with the specified message.
    */
   public function addOrDie($index, $data, $message) {
      if(!$data)
         $self->error($message);
      else
         $this->addData([$index => $data]);
   }

   public function userId() {
      return $this->user->userid;
   }

   public function addData($data, $message = '') {
      if ($message && !$data)
         $this->error($message);
      $this->response = array_merge($this->response, $data);
   }

   public function error($error, $respondImmediately = true) {
      $this->response['status'] = 'error';
      $this->response['errors'][] = $error;
      if ($respondImmediately)
         $this->respond();
   }

   public function respond() {
      if ($user = User::auth()) {
         $this->user = $user;
         $this->userid = $user->userid;
      }
      $this->response['userid'] = $this->userid ?: 'guest';
      print($this->json());
      die();
   }

   public function json() {
      return json_encode($this->response);
   }

}
