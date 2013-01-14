<?php
class UserRights
{
    public static function isUser(User $user)
    {
        return $user->getRights() & 0b000001;
    }

    public static function isAdmin(User $user)
    {
        return $user->getRights() & 0b100000;
    }

    public static function isBuhgalter(User $user)
    {
        return $user->getRights() & 0b000010;
    }

    public static function isDirector(User $user)
    {
        return $user->getRights() & 0b000100;
    }

    public static function isKladovschik(User $user)
    {
        return $user->getRights() & 0b010000;
    }

    public static function isPostavshchik(User $user)
    {
        return $user->getRights() & 0b001000;
    }

    public static function isGuest(User $user)
    {
        return !$user->getRights();
    }
}
