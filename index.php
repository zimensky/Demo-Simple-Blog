<?php
session_start();

define('ROOT', dirname(__FILE__));
define('SITE_URL', $_SERVER['HTTP_HOST']);

ini_set('display_errors', true);

date_default_timezone_set('Asia/Yakutsk');

require_once 'app/config.php';

Route::start();

function __autoload($class)
{
    if(file_exists('app/models/'.$class.'.php'))
        require_once 'app/models/'.$class.'.php';
    else if(file_exists('app/controllers/'.$class.'.php'))
        require_once 'app/controllers/'.$class.'.php';
    else if(file_exists('app/core/'.$class.'.php'))
        require_once 'app/core/'.$class.'.php';
    else if(file_exists('app/components/'.$class.'.php'))
        require_once 'app/components/'.$class.'.php';
}