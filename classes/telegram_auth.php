<?php
require_once(APP_PATH .'classes/class_sk_api_client.php');

/**
 * A class to aurhenticate users via Telegram.
 */
class TG_AUTH
{

    /**
     * The Name of the Bot used for auth.
     * @var object
     */
    private $API;

    /**
     * Initializes a new instance of the SK_API class.
     */
    public function __construct()
    {
        $this->API = new SK_API;
    }

    /**
     * Validate the Telegram Authorization against the Bot API.
     * @var array $auth_data
     */
    function checkTelegramAuthorization($auth_data) {

      return $this->API->check_tg_user_auth($auth_data);
    }
      
    /**
     * Login the user via Telegram callback.
     * @var array $auth_data
     */
    function login_user($auth_data) {
      if($this->checkTelegramAuthorization($auth_data)){
        $auth_data_json = json_encode($auth_data);
        setcookie('tg_user', $auth_data_json);
        $this->set_user_session($auth_data);
        return true;
      }
      return false;
    }

    /**
     * Login the user via cookie.
     */
    function login_user_via_cookie() {
      if (isset($_COOKIE['tg_user'])) {
        $auth_data_json = urldecode($_COOKIE['tg_user']);
        $auth_data = json_decode($auth_data_json, true);
        $this->set_user_session($auth_data);
        return true;
      }
      return false;
    }

    /**
     * Set the user session.
     * @var array $auth_data
     */
    function set_user_session($auth_data) {
      $_SESSION['username'] = $auth_data['username'];
      $_SESSION['photo_url'] = $auth_data['photo_url'];
      $_SESSION['id'] = $auth_data['id'];
      $_SESSION['hash'] = $auth_data['hash'];
      $_SESSION['auth_data'] = $auth_data;
    }

    /**
     * Unset the user session.
     */
    function unset_user_session() {
      session_destroy();
    }

    /**
     * Logout the user.
     */
    function logout_user() {
      setcookie('tg_user', '', time() - 3600);
      $this->unset_user_session();
    }

    /**
     * check if user has session.
     */
    function check_user_session() {
      if(isset($_SESSION)){
        if(isset($_SESSION['auth_data'])){
          return true;
        }
      }
      return false;
    }


}

?>
