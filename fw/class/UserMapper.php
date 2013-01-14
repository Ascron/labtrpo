<?php
/**
 * Created by JetBrains PhpStorm.
 * User: phoenix
 * Date: 07.01.13
 * Time: 22:10
 * To change this template use File | Settings | File Templates.
 */
class UserMapper
{
    public static function getList($offset = 0, $limit = 20, $order = 'uid ASC')
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT * FROM `users` ORDER BY {$order} LIMIT {$offset}, {$limit}");
        $list = array();
        while ($elem = $result->fetch_assoc()){
            $list[] = $elem;
        }

        return $list;
    }

    public static function create($login, $pass, $rights)
    {
        $db = Database::getInstance();
        $pass = md5($pass);
        $db->query("INSERT INTO `users`(login, password, rights) VALUES('{$login}', '{$pass}', '{$rights}')");
    }

    public static function edit()
    {

    }
}
