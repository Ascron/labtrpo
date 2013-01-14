<?php
class RequestsController extends AbstractController
{


    public function indexAction()
    {
        $view = View::getInstance();
        $view->assign('title', 'Запросы на поставку');
        $view->assign('header', 'Запросы на поставку');


        $view->assign('list', RequestMapper::getList());
        $view->render('rq-list.phtml');

    }

    public function getrAction($rid)
    {
        RequestMapper::take($rid);
        $view = View::getInstance();
        $view->assign('title', 'Запросы на поставку');
        $view->assign('header', 'Запросы на поставку');

        $view->assign('message', 'Вы успешно разместили заявку добавлен!');
        $view->assign('backLink', SITE_LOC . '/requests');


        $view->render('message.phtml');
    }

    public function pAction()
    {
        $view = View::getInstance();
        $view->assign('title', 'Запросы на поставку');
        $view->assign('header', 'Запросы на поставку');


        $view->assign('list', RequestMapper::getTakenList());
        $view->render('rq2-list.phtml');

    }

    public function getSide()
    {
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

        return $elems;
    }
}
