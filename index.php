<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
//$_SESSION['uid'] = 1;
require_once './fw/defines.php';

$q = $_GET['q'] ?: '';
Page::init($q);
exit;