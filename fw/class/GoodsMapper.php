<?php
class GoodsMapper
{
    public static function create()
    {

    }

    public static function edit($id, $count)
    {
        $db = Database::getInstance();
        $db->query("UPDATE `goods` SET count = {$count} WHERE id = {$id}");
    }

    public static function getList($search = '')
    {
        $db = Database::getInstance();
        if ($search){
            $result = $db->query("SELECT * FROM `goods` WHERE title LIKE '%{$search}%' ORDER BY id DESC");
        }
        else {
            $result = $db->query("SELECT * FROM `goods` ORDER BY id DESC");
        }

        $list = array();
        while ($elem = $result->fetch_assoc()){
            $list[] = $elem;
        }

        return $list;
    }
}
