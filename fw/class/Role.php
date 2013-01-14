<?php
/**
 * Created by JetBrains PhpStorm.
 * User: phoenix
 * Date: 07.01.13
 * Time: 22:16
 * To change this template use File | Settings | File Templates.
 */
class Role
{
    public static function getRoleByRights($rights)
    {
        if ($rights & 0b100000) return 'Администратор';
        if ($rights & 0b010000) return 'Кладовщик';
        if ($rights & 0b001000) return 'Поставщик';
        if ($rights & 0b000100) return 'Директор';
        if ($rights & 0b000010) return 'Бухгалтер';
        if ($rights & 0b000001) return 'Заказчик';

        return 'Гость';
    }
}
