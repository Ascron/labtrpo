<?php
/**
 * Created by JetBrains PhpStorm.
 * User: phoenix
 * Date: 07.01.13
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */
class User
{
    private $uid;
    private $login;
    private $password;
    private $rights;

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setRights($rights)
    {
        $this->rights = $rights;
    }

    public function getRights()
    {
        return $this->rights;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    private static $instance;

    private function __construct(){
        if (isset($_SESSION['uid']) && $_SESSION['uid']){
            $this->load($_SESSION['uid']);
        }
        else {
            $_SESSION['uid'] = 0;
            $this->load(0);
        }
    }


    private function __sleep(){}
    private function __wakeup(){}
    private function __clone(){}

    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function load($uid)
    {
        if ($uid == 0){
            $this->build(array());
        }
        else {
            $db = Database::getInstance();

            $result = $db->query("SELECT * FROM `users` WHERE uid = " . intval($uid) . " LIMIT 1");

            if ($userData = $result->fetch_assoc()){
                $this->build($userData);
            }
            else {
                throw new PageError('Ошибка при загрузке пользователя.', 500);
            }
        }
    }

    private function build($userData)
    {
        $this->setLogin($userData['login']);
        $this->setUid($userData['uid']);
        $this->setPassword($userData['password']);
        $this->setRights($userData['rights']);
    }
}
