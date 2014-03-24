<?php

/**
 * A User object class.
 * Allows for easy creation of a user and handles
 * both browser and API authentication. 
 * 
 * By  Scott Vanderlind
 * Early 2014
 */ 

class User {

   private static $singleton = false;

   private $row;
   public $username = 'nobody';
   public $userid = '';
   public $key = '';
   private static $cookieName = 'token';
   private static $headerName = 'HTTP_CARDS_KEY';

   public function __construct() {
      //Utils::logMe("User object created.");
   }

   /******************************
    * STATIC FACTORY FUNCTIONS.
    * Login, Signup, Auth, By Id
    ******************************/

   /**
    * auth(bool $api);
    * Returns the user object for the logged-in
    * user session. If $api is true, user token
    * will be pulled from header instead of cookie
    */
   public static function auth($api = false) {
      // If the singleton has been set, use it.
      if (static::$singleton) {
         return static::$singleton;
      }

      // Developer override for testing api with cookie login
      $get = idx($_GET, 'cookie', false);
      // Check if we're on the API or not, taking into account the
      // cookie-force flag.
      $api = $get ? false : URI::isActive('API', true);

      $user = new User();
      // Get the logged-in user's data
      if ($api) {
         $key = $user->getKey();
      } else {
         $key = $user->getCookie();
      }
      if (!$key) return false;

      $user->getRowBySession($key);
      if (!$user->loggedIn()){
         return false;
      }

      // Let's keep the logged in user around so we can use it
      // later/from other places.
      static::$singleton = $user;

      return $user;
   }

   /**
    * login(string $username, string $password);
    * Logs in a user by username/password.
    * Returns the user object on success.
    * Returns false on failure.
    * if $api is set, no cookie is set
    */
   public static function login($username, $password, $api = false) {
      $user = new User();
      $user->getRowByUsername($username);
      $user->updateData();
      if ($user->checkPass($password)) {
         $key = SessionManager::getSessionFor($user->userid) 
            ?: SessionManager::createSessionFor($user->userid);
         $user->key = $key;
      } else {
         // The user is not logged in.
         return false;
      }
      if (!$api)
         $user->writeCookie($key);
      static::$singleton = $user;
      return $user;
   }

   /**
    * signup()
    * Adds a user to the database, logs them in, too.
    */
   public static function signup($d) {
      $userid = AuthLib::newUser($d['username'], $d['password'],
         $d['firstname'], $d['lastname'], $d['email']);
      return self::byId($userid);
   }

   /**
    * byId(int $id);
    * Instantiates the user object by userid.
    * Useful for administrative stuff.
    */
   public static function byId($id) {
      $user = new User();
      $user->getRowByUserid($id);
      if (!$user->row)
         return false;
      return $user;
   }

   /**
    * search($term)
    * Searches for users by some term (first name, last name, username).
    */
   public static function search($term) {
      return DB::query("
         SELECT firstname, lastname, username, userid, email
         FROM users
         WHERE firstname LIKE %ss
         OR lastname LIKE %ss
         OR username LIKE %ss
         GROUP BY username", $term, $term, $term);
   }

   /******************************
    * OBJECT FUNCTIONS
    ******************************/

   /**
    * loggedIn()
    * returns t/f depending on whether the user is logged in.
    */
   public function loggedIn() {
      return (boolean)$this->row;
   }

   /**
    * checkPass(string $password);
    * Checks the provided password agains the one in the 
    * database. Returns true if it's correct.
    */
   public function checkPass($password) {
      if (!$this->row) {
         return false;
      }
      return CryptLib::checkHash($this->row['pw_hash'], $password);
   }

   /**
    * logout()
    * Removes the user's session from SessionManager
    */
   public function logout() {
      SessionManager::deleteSession($this->row['api_key']);
   }

   /**
    * updateData()
    * Snycs the user object's members with the row.
    */
   public function updateData() {
      if (!$this->row) {
         return false;
      }
      $this->username = $this->row['username'];
      $this->userid = $this->row['userid'];
      return $this->username;
   }

   /**
    * writeCookie()
    * Sets a cookie in the browser.
    * The value is the api_key from the user's row.
    */
   private function writeCookie($key = false) {
      setcookie(static::$cookieName, $key ?: $this->row['api_key'],
       time() + 86400, '/');
   }

   /**
    * getCookie()
    * Gets the cookie from the browser.
    */
   private function getCookie() {
      return idx($_COOKIE, static::$cookieName, false);
   }

   /**
    * getKey()
    * Gets the API key from the header of the request.
    */
   private function getKey() {
      return idx($_SERVER, static::$headerName, false);
   }


   /******************************
    * DATABASE-TOUCHING STUFF IS DOWN HERE.
    ******************************/
   private function getRowByUserid($userid) {
      $this->row = DB::queryFirstRow("
         SELECT * 
         FROM users 
         LEFT JOIN sessions USING (`userid`)
         WHERE userid = %i", $userid);
      $this->updateData();
   }
   private function getRowBySession($key) {
      $this->row = DB::queryFirstRow("
         SELECT * 
         FROM users 
         LEFT JOIN sessions USING (`userid`)
         WHERE api_key = %s", $key);
      $this->updateData();
   }
   private function getRowByUsername($username) {
      $this->row = DB::queryFirstRow("
         SELECT * 
         FROM users 
         LEFT JOIN sessions USING (`userid`)
         WHERE username = %s", $username);
      $this->updateData();
   }


}
