<?php  
session_start();
date_default_timezone_set('America/Sao_Paulo');
define("PATH_FULL","http://localhost/teste/git/Mvc/Views/pages/");
class Aplication{
	public function run(){
		$url = isset($_GET['url']) ? explode('/',$_GET['url'])[0] : 'Home';
		$url = ucfirst($url);
		$url.= 'Controller';
		if(file_exists('Controllers/'.$url.'.php')){
		$class = 'Controllers\\'.$url;
		$classNew = new $class;
		$classNew->index();
		}else{
			die('Não existe essa pagina');
		}
	}
}