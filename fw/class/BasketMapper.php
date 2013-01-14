<?php
class BasketMapper
{
    public static function addGood($id, $count)
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT * FROM `goods` WHERE id = {$id}");
        $good = $result->fetch_assoc();
        if ($good['count']<$count){
            throw new PageError('Данное количество заказать нельзя', 0);
        }



        $user = User::getInstance();
        $result = $db->query("INSERT INTO `orders` VALUES ({$user->getUid()}, {$id}, {$count}) ON DUPLICATE KEY UPDATE count = count + {$count}");
    }

    public static function orderGoods()
    {}

    public static function editGood()
    {}

    public static function show()
    {
        $db = Database::getInstance();
        $user = User::getInstance();
        $result = $db->query("SELECT g.*,o.count as count  FROM `orders` o LEFT JOIN `goods` g ON g.id = o.id WHERE o.uid = {$user->getUid()}");
        $list = array();
        while ($item = $result->fetch_assoc()){
            $list[] = $item;
        }

        return $list;
    }
}
