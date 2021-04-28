<?php
// define("START_TIME", microtime(true));
// echo "workin time is: ". (microtime(true)-START_TIME)
ini_set('display_errors', 1);
ini_set('session.gc_maxlifetime', 0);

use application\core\router;


session_start();


spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class.'.php');
	if(file_exists($path))
		require $path;
});



$router = new Router();
$router->route();