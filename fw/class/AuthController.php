<?php
class AuthController extends AbstractController
{
    public function logoutAction()
    {
        $_SESSION['uid'] = 0;
        header('Location: '.SITE_LOC);
    }

    public function loginAction($uid)
    {
        $_SESSION['uid'] = $uid;
        header('Location: '.SITE_LOC);
    }
}
