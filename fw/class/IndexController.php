<?php
class IndexController extends AbstractController
{
    public function indexAction($param = '')
    {
        $view = View::getInstance();
        $view->assign('title', 'Главная страница');
        $view->assign('header', 'Главная страница');


        $view->render('index.phtml');
    }

    public function regAction(){}
    public function aboutAction(){}

    public function getSide()
    {
        $elems = array();

        if (UserRights::isGuest(User::getInstance())){
            $elems[] = array(
                'title' => 'Зарегистрироваться',
                'href'  => SITE_LOC . '/index/reg',
                'active'=> 0
            );

            $elems[] = array(
                'title' => 'Авторизация',
                'href'  => SITE_LOC . '/index/auth',
                'active'=> 0
            );

            $elems[] = array(
                'title' => 'Контактная информация',
                'href'  => SITE_LOC . '/index/about',
                'active'=> 0
            );
        }

        if (UserRights::isAdmin(User::getInstance())){
            $elems[] = array(
                'title' => 'Список пользователей',
                'href'  => SITE_LOC . '/admin/list',
                'active'=> 0
            );

            $elems[] = array(
                'title' => 'Добавить пользователя',
                'href'  => SITE_LOC . '/admin/add',
                'active'=> 0
            );
        }

        if (UserRights::isUser(User::getInstance()) || UserRights::isKladovschik(User::getInstance())){
            $elems[] = array(
                'title' => 'Список товаров',
                'href'  => SITE_LOC . '/goods',
                'active'=> 0
            );
        }

        if (UserRights::isUser(User::getInstance())){

            $elems[] = array(
                'title' => 'Корзина',
                'href'  => SITE_LOC . '/goods/basket',
                'active'=> 0
            );
        }

        if (UserRights::isPostavshchik(User::getInstance())){

            $elems[] = array(
                'title' => 'Поставки',
                'href'  => SITE_LOC . '/requests',
                'active'=> 0
            );

            $elems[] = array(
                'title' => 'Поданные заявки',
                'href'  => SITE_LOC . '/requests/p',
                'active'=> 0
            );

            $elems[] = array(
                'title' => 'Осуществляемые поставки',
                'href'  => SITE_LOC . '/requests/d',
                'active'=> 0
            );
        }

        return $elems;
    }
}
