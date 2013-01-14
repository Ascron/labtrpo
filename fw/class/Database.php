<?php
class Database
{
    private static $instance;

    private function __construct(){}
    private function __sleep(){}
    private function __wakeup(){}
    private function __clone(){}

    private $mysqli;

    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
            self::$instance->setMysqli(new mysqli);
        }
        if (!self::$instance->isConnected()){
            self::$instance->connect();
        }

        return self::$instance;

    }

    public function connect()
    {
        $link = $this->getMysqli()->connect('localhost', 'root', '123456', 'trpo');
    }

    public function setMysqli(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->connect();
    }

    /**
     * @return mysqli
     */
    public function getMysqli()
    {
        return $this->mysqli;
    }

    private function isConnected()
    {
        return $this->getMysqli()->ping();
    }

    public function query($query)
    {
        return $this->getMysqli()->query($query);
    }
}
