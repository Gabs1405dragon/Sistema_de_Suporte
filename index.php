<?php  
define('BASE3','http://localhost/suporte/');
define('BASE2','http://localhost/suporte/views/pages/');
require('vendor/autoload.php');
$autoload = function($class){

	include($class.'.php');
};

spl_autoload_register($autoload);

$aplication = new Aplication;
$aplication->run();