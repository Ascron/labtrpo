<?php
/**
 * Created by JetBrains PhpStorm.
 * User: phoenix
 * Date: 07.01.13
 * Time: 9:05
 * To change this template use File | Settings | File Templates.
 */
abstract class AbstractController
{
    private $action;
    private $params;

    public function __construct($params)
    {
        $this->setAction(array_shift($params));

        $this->setParams($params);
    }

    public function callAction($action = '')
    {
        $action = $this->getAction() ?: $action;
        if ($this->actionExists($action)){
            call_user_func_array(array($this, $this->getActionName($action)), $this->getParams());
        }
    }

    public function actionExists($action)
    {
        $action = $this->getActionName($action);
        return method_exists($this, $action);
    }

    private function getActionName($action)
    {
        return $action . 'Action';
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function init(){}

    public function getSide(){
        return array();
    }
}
