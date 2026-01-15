<?php
include_once("storage/storage.php");

class Auth {
  private $user_storage;
  private $user = NULL;

  public function __construct(IStorage $user_storage) {
    $this->user_storage = $user_storage;

    if (isset($_SESSION["user"])) {
      $this->user = $_SESSION["user"];
    }
  }

  public function register($data) {
    $user = [
      'username' => $data['username'],
      'password' => password_hash($data['password'], PASSWORD_DEFAULT),
      'email' => $data['email'],
      'money' => 10000,
      'roles' => ["user"],
      'cards' => [],
      'limit' => 5,
      'slotprice' => 100
    ];
    return $this->user_storage->add($user);
  }

  public function user_exists($username) {
    $users = $this->user_storage->findOne(['username' => $username]);
    return !is_null($users);
  }

  public function authenticate($username, $password) {
    $users = $this->user_storage->findMany(function ($user) use ($username, $password) {
      return $user["username"] === $username && 
             password_verify($password, $user["password"]);
    });
    return count($users) === 1 ? array_shift($users) : NULL;
  }
  
  //checks if user is logged in
  public function is_authenticated() {
    return !is_null($this->user);
  }

  public function authorize($roles = []) {
    if (!$this->is_authenticated()) {
      return FALSE;
    }
    foreach ($roles as $role) {
      if (in_array($role, $this->user["roles"])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function login($user) {
    $this->user = $user;
    $_SESSION["user"] = $user;
  }

  public function logout() {
    $this->user = NULL;
    unset($_SESSION["user"]);
  }

  public function checkIfLoggedIn(){
    if($this->is_authenticated()){
      die("You are already logged in! Please go back! <a href=\"home.php\">Home</a>");
    }
  }

  function isAdmin($auth){
    if(!$auth->is_authenticated())
        return false;

    if($auth->authenticated_user()["id"] === "65988d0d7c371")
        return true;

    return false;
}

  public function authenticated_user() {
    return $this->user;
  }
}