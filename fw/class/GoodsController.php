<?php
class GoodsController extends AbstractController
{
    public function indexAction()
    {
        $view = View::getInstance();
        $view->assign('title', 'Товары');
        $view->assign('header', 'Товары');


        $view->assign('list', GoodsMapper::getList());
        $view->render('goods-list.phtml');
    }

    public function searchAction()
    {
        $search = $_POST['search'];

        $view = View::getInstance();
        $view->assign('title', 'Товары');
        $view->assign('header', 'Товары');

        $view->assign('search', $search);
        $view->assign('list', GoodsMapper::getList($search));
        $view->render('goods-list.phtml');
    }

    public function basketAction($id = 0, $count = 0)
    {
        if ($id && $count){
            $view = View::getInstance();
            BasketMapper::addGood($id, $count);

            $view->assign('title', 'Корзина');
            $view->assign('header', 'Корзина');

            $view->assign('message', 'Товар успешно добавлен!');


            $view->render('message-basket.phtml');
        }
        else {
            $view = View::getInstance();
            $view->assign('title', 'Корзина');
            $view->assign('header', 'Корзина');

            $view->assign('list', BasketMapper::show());
            $view->render('goods-basket.phtml');
        }
    }

    public function editAction($id = 0, $count = 0)
    {
        $view = View::getInstance();
        GoodsMapper::edit($id, $count);

        $view->assign('title', 'Товары');
        $view->assign('header', 'Товары');

        $view->assign('message', 'Товар успешно обновлен!');


        $view->render('message-basket.phtml');
    }

    public function getSide()
    {
        $elems = array();

        $elems[] = array(
            'title' => 'Список товаров',
            'href'  => SITE_LOC . '/goods',
            'active'=> 0
        );

        if (UserRights::isUser(User::getInstance())){
            $elems[] = array(
                'title' => 'Корзина',
                'href'  => SITE_LOC . '/goods/basket',
                'active'=> 0
            );
        }



        return $elems;
    }


}
