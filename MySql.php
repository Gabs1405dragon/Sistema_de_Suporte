<?php  
define('DADOS',array('dbname'=>'suporte','host'=>'localhost','port'=>'3332','user'=>'root','senha'=>'123456'));

class MySql{
private static $pdo;

	public static function connect(){
		if(self::$pdo == null){
		try{
		self::$pdo = new PDO('mysql:dbname='.DADOS['dbname'].';host='.DADOS['host'].';port='.DADOS['port'],DADOS['user'],DADOS['senha'],array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));
		}catch(Exception $e){
			echo 'erro ao conectar...'	;
			}
		}
		return self::$pdo;
	}
}