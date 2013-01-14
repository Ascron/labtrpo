<?php
class Page
{
    private $query;
    private $controller;

    public static function init($query)
    {
        View::setLayer('main.phtml');
        $page = new Page($query);

        try {
            User::getInstance();
            $page->loadController($page->parseQuery());
            $page->callControllerAction('index');
        }
        catch (PageError $e) {
            $page->callErrorController($e);
        }
    }

    public function __construct($q = '')
    {
        $this->setQuery($q);
    }

    public function parseQuery()
    {
        $parts = explode('/',$this->getQuery());
        return array_filter($parts);
    }

    private function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setController(AbstractController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return AbstractController
     */
    public function getController()
    {
        return $this->controller;
    }

    public function loadController($params)
    {
        if (!$params[0]){
            $params[0] = 'index';
        }


        $controllerName = ucfirst($params[0]) . 'Controller';

        array_shift($params);
        $controller = new $controllerName($params);

        $this->setController($controller);
    }

    public function callControllerAction($action = '')
    {
        View::getInstance()->setController($this->getController());
        $this->getController()->init();
        $this->getController()->callAction($action);
    }

    public function callErrorController(PageError $e)
    {
        $ec = new ErrorController(array('index', $e));
        View::getInstance()->setController($ec);
        $ec->init();
        $ec->callAction();
    }
}
