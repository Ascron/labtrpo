<?php
class View
{
    private $params = array();
    private $controller;

    private static $instance;
    private static $layer;

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}

    private function __sleep(){}

    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function assign($key, $value)
    {
        $this->params[$key] = $value;
    }

    private function _get($key)
    {
        return $this->params[$key];
    }

    public static function get($key)
    {
        return View::getInstance()->_get($key);
    }

    public function render($template, $withLayer = true)
    {
        if ($withLayer){
            $this->assign('template', $template);
            include_once(VIEW_DIR . '/' . $this->getLayer());
        }
        else {
            include_once(VIEW_DIR . '/' . $template);
        }
    }

    public static function layer($template)
    {
        $view = View::getInstance();
        $view->setLayer($template);
    }

    public static function setLayer($layer)
    {
        self::$layer = $layer;
    }

    public static function getLayer()
    {
        return self::$layer;
    }

    public function setController($controller)
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
}

