<?php  
namespace Controllers;

class HomeController{
	private $view;

	public function __construct(){
		$this->view = new \Views\MainView();
	}

	public function index(){
		
		$this->view::render(['titulo'=>'suporte'],'home');
	}
}