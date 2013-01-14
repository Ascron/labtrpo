<?php
class RequestMapper
{
    public static function take($rid)
    {
        $db = Database::getInstance();
        $user = User::getInstance();
        $result = $db->query("INSERT INTO `request_dos` VALUES ({$user->getUid()},{$rid})");
    }

    public static function accept(){}
    public static function decline(){}

    public static function getList()
    {
        $db = Database::getInstance();
        $user = User::getInstance();
        $result = $db->query("SELECT *, r.count as count FROM `requests` r LEFT JOIN `goods` g ON r.id=g.id");
        $list = array();
        while ($elem = $result->fetch_assoc()){
            $list[] = $elem;
        }
        return $list;
    }

    public static function getTakenList()
    {
        $db = Database::getInstance();
        $user = User::getInstance();
        $result = $db->query("SELECT *, r.count as count FROM `request_dos` rd LEFT JOIN `requests` r ON r.rid=rd.rid LEFT JOIN goods g ON r.id=g.id  WHERE rd.uid={$user->getUid()}");

        $list = array();
        while ($elem = $result->fetch_assoc()){
            $list[] = $elem;
        }

        return $list;
    }

    public static function check($rid)
    {
        $user = User::getInstance();
        $db = Database::getInstance();

        $result = $db->query("SELECT * FROM `request_dos` WHERE rid = {$rid} AND uid = {$user->getUid()}");
        return !$result->num_rows;
    }
}
