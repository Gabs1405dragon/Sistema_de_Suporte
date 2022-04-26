<?php 
namespace Controllers;

class AdminController{
    private $view;
    public function __construct(){
        $this->view = new \Views\MainView();
    }

    public function index(){
        $this->view::render(['titulo'=>'Administrador'],'admin');
    }
}