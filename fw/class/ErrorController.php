<?php
class ErrorController extends AbstractController
{
    public function indexAction(PageError $e)
    {
        $view = View::getInstance();
        $view->assign('errorCode', $e->getResultCode());
        $view->assign('message', $e->getMessage());
        $view->assign('title', 'Произошла ошибка!');
        $view->assign('header', 'Ой! Ошибка!');


        $view->render('error.phtml');
    }

    public function getSide()
    {
        $elems[] = array(
            'title' => 'Вернуться назад',
            'href'  => 'javascript:history.back()',
            'active'=> 0
        );

        return $elems;
    }
}
