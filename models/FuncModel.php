<?php 
namespace models;

class FuncModel{
	public static function pegarPost($post){
		if(isset($_POST[$post])){
			echo $_POST[$post];
		}
	}

	public static function existeToken(){
		$token = $_GET['token'];
		$chamado = \MySql::connect()->prepare("SELECT * FROM chamados WHERE token = ? ");
		$chamado->execute(array($token));
		if($chamado->rowCount() == 1){
			return true;
		}else{
			return false;
		}
	}

	public static function getPrgunta($token){
		$pergunta = \MySql::connect()->prepare("SELECT * FROM chamados WHERE token = ?");
		$pergunta->execute(array($token));
		return $pergunta->fetch();
	}

}