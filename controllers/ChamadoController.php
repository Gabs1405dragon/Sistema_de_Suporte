<?php  
namespace Controllers;
class ChamadoController{
    private $view;
    public function __construct(){
        $this->view = new \Views\MainView();
    }

    public function index(){
        if(isset($_GET['token'])){
            if(\models\FuncModel::existeToken()){
                $info = \models\FuncModel::getPrgunta($_GET['token']);
                $this->view::render(['titulo'=>'Um novo chamado'],'chamado',$info);
            }else{
                echo 'Esse token não existe no banco de dados...';
            }
        }   
    }

}