<?php

define('DEBUG', true);
define('FW_DIR', __DIR__);
define('VIEW_DIR', FW_DIR . '/view');
define('CONTROLLER_DIR', FW_DIR . '/controller');
define('LIB_DIR', FW_DIR . '/class');

define('SITE_LOC', '/labtrpo');
define('JS_DIR', SITE_LOC . '/js');
define('CSS_DIR', SITE_LOC . '/css');
define('IMG_DIR', SITE_LOC . '/img');

function __autoload($classname) {
    if(!file_exists(LIB_DIR . "/". $classname .".php")){
        throw new PageError('Произошла ошибка!', 500);
    }

    include_once(LIB_DIR . "/". $classname .".php");
}