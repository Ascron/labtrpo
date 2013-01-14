<?php
class AdminController extends AbstractController
{
    public function init()
    {
        if (!UserRights::isAdmin(User::getInstance())){
            throw new PageError('Недостаточно прав для доступа к данной странице.', 403);
        }
    }

    public function indexAction()
    {
        $view = View::getInstance();
        $view->assign('title', 'Админпанель');
        $view->assign('header', 'Админпанель');


        $view->render('admin.phtml');
    }

    public function listAction()
    {
        $view = View::getInstance();
        $view->assign('title', 'Админпанель');
        $view->assign('header', 'Список пользователей');


        $view->assign('list', UserMapper::getList(0, 1000));


        $view->render('admin-list.phtml');
    }

    public function addAction($ajax = '')
    {
        if ($ajax){
            UserMapper::create($_POST['login'],$_POST['password'],$_POST['role']);
            $view = View::getInstance();
            $view->assign('title', 'Админпанель');
            $view->assign('header', 'Добавление пользователя');

            $view->assign('message', 'Пользователь успешно добавлен!');
            $view->assign('backLink', SITE_LOC . '/admin/add');


            $view->render('message.phtml');
        }
        else {
            $view = View::getInstance();
            $view->assign('title', 'Админпанель');
            $view->assign('header', 'Добавление пользователя');


            $view->render('admin-add.phtml');
        }
    }

    public function getSide()
    {
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

        return $elems;
    }
}
