<?php
class User {

  public $database = null;

  public function __construct() {
    $this->database = DataBase::connect();
  }

 /**
  * Проверка на Администратора
  */
  public static function isAdmin() {
    return $_SESSION['is_admin'] ? true : false;
  }

  /**
   * @date 30 July 2018
   * @time 12:47
   *
   */
  public static function getLang() {
    if(isset($_SESSION['user_lang']) || !empty($_SESSION['user_lang'])) {
      return $_SESSION['user_lang'];
    }
    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      return htmlspecialchars(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)); 
    }
    return 'ru';
  }

  /**
   * @date 31 July 2018
   * @time 16:48
   *
   */
  public static function isAuth() {
    if(isset($_SESSION['user_id']) || !empty($_SESSION['user_id'])) {
      return true;
    }
    return false;
  }

  /**
   * @date 30 July 2018
   * @time 14:47
   *
   */
	public static function getIP() {
    if(isset($_SERVER['REMOTE_ADDR']) || !empty($_SERVER['REMOTE_ADDR'])) {
      if(filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
      }
    }
    return '0.0.0.0';
	}

  public function getInfo($user_id, $fields='`first_name`, `last_name`, `email`') {
    if(!isset($user_id) || empty($user_id) || !is_numeric($user_id)) {
      return '...';
    }
    $sql = "SELECT $fields FROM `users` WHERE `id` = :user_id";
    $get_info = $this->database->prepare($sql);
    $get_info->execute(array(':user_id' => $user_id));
    return $get_info->fetch(PDO::FETCH_ASSOC);
  }

  public function getInitials($user_id) {
    $info = $this->getInfo($user_id, '`first_name`, `last_name`');
    return $info['first_name'].' '.$info['last_name'];
  }

  /**
   * @desc Выход
   * @date 9.04.2016
   */
  public function logOut() {
    session_unset();
    session_destroy();
  }

}
?>